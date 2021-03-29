<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Yajra\Datatables\Datatables;
use App\Http\Requests\Admin\CreateRequest;
use App\Http\Requests\Admin\EditRequest;
use App\Http\Requests\Admin\DeleteRequest;
use Throwable;

class AdminController extends Controller
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
                $admins = Admin::latest()->get();
                $start  = $request->get('start');

                return Datatables::of($admins)
                        ->addColumn('sl', function($model) use ($start) {
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
                                <a id="btn-show-'.$model->id.'" class="btn btn-info" 
                                    href="'.route("admin.auth.show", $model->id).'" role="button">
                                    <i class="fa fa-search"></i>
                                </a>

                                <a class="btn btn-warning" 
                                    href="'.route("admin.auth.edit", $model->id).'" role="button">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                
                                <button type="button" id="'.$model->id.'" name="'.$model->id.'" onclick="deleteSingle(this.id)" class="btn btn-danger btn-delete"><i class="fas fa-trash"></i>
                                </button>
                            ';
                            return $action;
                        })
                        ->rawColumns(['checkbox', 'action'])->make(true);
            }
            return view('admin.pages.auth.all');
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
        return view('admin.pages.auth.createOrEdit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\CreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $data = [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ];

        Admin::create($data);
        return response()->json([
            'messages' => 'Create Successfully',
            'success' => true,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $admin = Admin::findOrFail($id); 
        $view  = view('admin.pages.auth.view', compact('admin'))->render();
        return response()->json([
            'messages' => $view,
            'success' => true,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.pages.auth.createOrEdit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\EditRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditRequest $request, $id)
    {
        try {
            $data = [
                'name'     => $request->name,
                'email'    => $request->email,
            ];

            if (!empty($request->password && $request->password_confirmation)) {
                $data['password'] = bcrypt($request->password);
            }

            Admin::findOrFail($id)->update($data);

            return response()->json([
                'messages' => 'Update Successfully',
                'success' => true,
            ], 202);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Admin::findOrFail($id)->delete();
            return response()->json([
                'messages' => 'Delete Successfully',
                'success' => true,
            ], 202);
        } catch (Throwable $exception) {
            return response()->json([
                'messages' => $exception->getMessage(),
                'error' => true,
            ]);
        }
    }

    /**
     * Remove mange the specified resource from storage.
     *
     * @param  \App\Http\Requests\Admin\DeleteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(DeleteRequest $request)
    {
        try {
            $arrayID = $request->validated();

            Admin::findOrFail($arrayID['checked']);
            Admin::destroy($arrayID['checked']);

            return response()->json([
                'messages' => 'Delete Manage Successfully',
                'success' => true,
            ], 202);

        } catch (Throwable $exception) {
            return response()->json([
                'messages' => $exception->getMessage(),
                'error' => true,
            ]);
        }
    }
}
