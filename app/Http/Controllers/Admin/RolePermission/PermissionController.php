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
use Throwable;
use Yajra\Datatables\Datatables;
use DB;

class PermissionController extends Controller
{

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
        return view('admin.pages.role_permission.permission.create');
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
            $data = $request->validated();

            foreach ($data['name'] as $name) {
                $permission = Permission::create([
                    'name' => $data['group_name']. '.' .$name,
                    'group_name' => $data['group_name'],
                ]);
            }
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
