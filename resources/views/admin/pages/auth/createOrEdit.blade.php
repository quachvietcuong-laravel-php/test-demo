@extends('admin.pages.master')

@section('admin-title')
    Admin {{ isset($admin) ? "- Edit Admin Accounts". $admin->name : '- Create Admin Accounts' }}
@endsection

@section('admin-content')
	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ isset($admin) ? "Edit " : 'Create' }} Admin Accounts</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Accounts</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Admin Information</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="form-createOrEdit" action="" method="POST">
                    @isset($admin)
                        @method('PUT')
                    @endisset

                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">User Name</label>
                            <input id="name" type="name" name="name" class="form-control" 
                                value="{{ isset($admin) ? old('name', $admin->name) : '' }}" 
                                placeholder="Enter User Name">
                            <span id="errors-name" class="error text-danger hidden"></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" name="email" class="form-control" 
                                value="{{ isset($admin) ? old('email', $admin->email) : '' }}" 
                                placeholder="Enter email">
                            <span id="errors-email" class="error text-danger hidden"></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                            <span id="errors-password" class="error text-danger hidden"></span>
                        </div>
                        <div class="form-group">
                            <label for="password-confirm">{{ __('Confirm Password') }}</label>                  
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Password confirm">
                            <span id="errors-password_confirmation" class="error text-danger hidden"></span>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn-primary">Save</button>
                    </div>
                </form>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function ajaxCreateOrUdate(url, method, data, idForm) {
            $.ajax({
                url: url,
                type: method,
                data: data,
                success: function(response){
                    if (response.error) {
                        // Show error message and fade it
                        showAndFadeErrorText('#errors-name', response.messages.name);
                        showAndFadeErrorText('#errors-email', response.messages.email);
                        showAndFadeErrorText('#errors-password', response.messages.password);
                        showAndFadeErrorText('#errors-password_confirmation', response.messages.password_confirmation);
                    }else{
                        switch (method) {
                            case 'PUT':
                                Swal.fire('Good job!', response.messages, 'success');
                                break;
                            default:
                                alertAndResetForm(response.messages, idForm);                              
                                break;
                        }
                    }
                }
            });
        }

        const url    = '{{ (isset($admin)) ? route('admin.auth.update', $admin->id) : route('admin.auth.store') }}';
        const idForm = 'form-createOrEdit';

        $("#" + idForm).submit(function(event) {
            event.preventDefault();
            const data = $(this).serialize();
            console.log(data)
            switch ($('input[name="_method"]').val()) {
                case 'PUT':
                    ajaxCreateOrUdate(url, 'PUT', data, idForm);
                    break;
                default:
                    ajaxCreateOrUdate(url, 'POST', data, idForm);
                    break;
            }
        });
    </script>
@endpush