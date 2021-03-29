@extends('admin.pages.master')

@section('admin-title')
    Role - Create Role
@endsection

@section('admin-content')
	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Create Role</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Role</li>
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
                    <h3 class="card-title">Role Information</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="form-create" action="" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Role Name</label>
                            <input id="name" type="name" name="name" class="form-control" value="" placeholder="Enter Role Name">
                            <span id="errors-name" class="error text-danger hidden"></span>
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
        // Create role by ajax
        const idForm = 'form-create';
        $("#" + idForm).submit(function(event) {
            event.preventDefault();
            const data = $(this).serialize();

            $.ajax({
                url: '{{ route('admin.role_permission.role.store') }}',
                type: 'POST',
                data: data,
                success: function(response){
                    if (response.error) {
                        // Show error message and fade it
                        showAndFadeErrorText('#errors-name', response.messages.name);
                    }else{
                        alertAndResetForm(response.messages, idForm);                              
                    }
                }
            });
        });
    </script>
@endpush