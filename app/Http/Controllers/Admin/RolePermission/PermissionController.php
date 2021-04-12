<?php

namespace App\Http\Controllers\Admin\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Http\Requests\RolePermission\Permission\CreateRequest;
use App\Http\Requests\RolePermission\Permission\DeleteRequest;
use App\Http\Requests\RolePermission\Permission\EditRequest;
use Throwable;
use Yajra\Datatables\Datatables;
use DB;
use Carbon\Carbon;

class PermissionController extends Controller
{
    private function createData($names, $group_name) 
    {
        $data  = [];
        $guard = config('auth.defaults.guard');
        foreach ($names as $name) {
            $data[] = [
                'group_name' => $group_name,
                'name'       => $group_name . '.' .$name,
                'guard_name' => $guard,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        return $data;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $permissions = Permission::select('group_name', DB::raw('GROUP_CONCAT(name) as names'))
                    ->groupBy('group_name')
                    ->latest()
                    ->get();

                    return Datatables::of($permissions)
                        ->addColumn('sl', function($model) {
                            return $model->index + 1;
                        })
                        ->addColumn('name', function($model) {
                            $permissionName = '';
                            $names = explode(',', $model->names);
                            foreach ($names as $name) {
                                $permissionName .= '<span class="badge badge-info mr-2">'.$name.'</span>';
                            }
                            return $permissionName;
                        })
                        ->addColumn('checkbox', function($model) {
                            $checkbox = '
                                <div class="text-center">
                                    <input type="checkbox" onclick="itemChecked(this.name)" class="checkItem" value="'.$model->group_name.'" name="checked[]">
                                </div>
                            ';
                            return $checkbox;
                        })
                        ->addColumn('action', function($model) {
                            $action = '
                                <a class="btn btn-warning" 
                                    href="'.route("admin.role_permission.permission.edit", $model->group_name).'" role="button">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>

                                <button type="button" id="'.$model->group_name.'" name="" onclick="deleteSingle(this.id)" class="btn btn-danger btn-delete"><i class="fas fa-trash"></i>
                                </button>
                            ';
                            return $action;
                        })
                        ->rawColumns(['checkbox', 'action', 'name'])
                        ->make(true);
            }
            return view('admin.pages.role_permission.permission.all');
        } catch (Throwable $exception) {
            abort(404, $exception->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('admin.pages.role_permission.permission.createOrEdit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\RolePermission\Permission\CreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        try {
            $validated = $request->validated();
            $data      = $this->createData($validated['name'], $validated['group_name']);
            DB::table('permissions')->insert($data);
            return response()->json([
                'messages' => 'Create Successfully',
                'success' => true,
            ], 201);
        } catch (Throwable $exception) {
            return response()->json([
                'messages' => $exception->getMessage(),
                'error' => true,
            ]);
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $value
     * @return \Illuminate\Http\Response
     */
    public function edit($value)
    {
        $data = DB::table('permissions')
            ->where('group_name', $value)
            ->select('group_name', DB::raw('GROUP_CONCAT(name) as names'))
            ->groupBy('group_name')
            ->get();
            
        $permission = $data[0];
        return view('admin.pages.role_permission.permission.createOrEdit', compact('permission'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\RolePermission\Permission\EditRequest  $request
     * @param  string  $value (group_name in url)
     * @return \Illuminate\Http\Response
     */
    public function update(EditRequest $request, Permission $permiss, $value)
    {
        $data = $request->validated();
        $permissions = DB::table('permissions')
            ->where('group_name', $value)
            ->pluck('name')
            ->toArray();
        $namesExist   = replaceGroupName($permissions, $value);
        $namesRequest = $data['name'];

        $guard = config('auth.defaults.guard');
        if (!(array_values($namesRequest) == array_values($namesExist))) {
            $uniqueDatas = array_intersect($namesRequest, $namesExist);
            $createDatas = array_diff($namesRequest, $uniqueDatas);
            $deleteDatas = array_diff($namesExist, $uniqueDatas);

            // Insert data
            if (count($createDatas) > 0) {    
                $dataCreate = $this->createData($createDatas, $data['group_name']);
                DB::table('permissions')->insert($dataCreate);
            }

            // Delete data
            if (count($deleteDatas) > 0) {
                $dataDelete = [];
                foreach ($deleteDatas as $deleteData) {
                    $dataDelete[] = [
                        'name' => $request->group_name . '.' .$deleteData,
                    ];
                }
                DB::table('permissions')->whereIn('name', $dataDelete)->delete();
            }
        }
        return response()->json([
            'messages' => 'Update Successfully',
            'success' => true,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $group_name
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $group_name)
    {
        try{
            $permissions = DB::table('permissions')
                ->where('group_name', $group_name)
                ->delete();
            return response()->json([
                'messages' => 'Delete Successfully',
                'success' => true,
            ], 201);
            
        } catch (Throwable $exception) {
            return response()->json([
                'messages' => $exception->getMessage(),
                'error' => true,
            ]);
        }
    }

    /**
     * Remove multiple the specified resource from storage.
     *
     * @param  App\Http\Requests\RolePermission\Permission\DeleteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(DeleteRequest $request)
    {
        try{
            $group_names = $request->validated();
            foreach ($group_names['checked'] as $group_name) {
                $permissions = DB::table('permissions')
                ->where('group_name', $group_name)
                ->delete();
            }
            return response()->json([
                'messages' => 'Delete Manage Successfully',
                'success' => true,
            ], 201);
        } catch (Throwable $exception) {
            return response()->json([
                'messages' => $exception->getMessage(),
                'error' => true,
            ]);
        }
    }
}
