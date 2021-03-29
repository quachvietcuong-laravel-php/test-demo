<?php

namespace App\Http\Controllers\Admin\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Http\Requests\RolePermission\Role\CreateRequest;
use App\Http\Requests\RolePermission\Role\DeleteRequest;
use Throwable;
use Yajra\Datatables\Datatables;

class RoleController extends Controller
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
                $roles = Role::latest()->get();

                return Datatables::of($roles)
                        ->addColumn('sl', function($model) {
                            return $model->id;
                        })
                        ->addColumn('checkbox', function($model) {
                            $checkbox = '
                                <div class="text-center">
                                    <input type="checkbox" onclick="itemChecked(this.name)" class="checkItem" value="'.$model->id.'" name="checked[]">
                                </div>
                            ';
                            return $checkbox;
                        })
                        ->addColumn('action', function($model) {
                            $action = '
                                <button type="button" id="'.$model->id.'" name="'.$model->id.'" onclick="deleteSingle(this.id)" class="btn btn-danger btn-delete"><i class="fas fa-trash"></i>
                                </button>
                            ';
                            return $action;
                        })
                        ->rawColumns(['checkbox', 'action'])->make(true);
            }
            return view('admin.pages.role_permission.role.all');
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
        return view('admin.pages.role_permission.role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $role = Role::create($request->validated());
        return response()->json([
            'messages' => 'Create Successfully',
            'success' => true,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $role = Role::findById($id);
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

    public function delete(DeleteRequest $request)
    {        
        try{
            $ids = $request->validated();
            foreach ($ids['checked'] as $id) {
                $role = Role::findById($id)->delete();
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
