@extends('admin.pages.master')

@section('admin-title')
    Permission - Create Permission
@endsection

@section('styles')
    <style type="text/css">
        .margin-label{
            margin-right: 30px;
        }
    </style>
@endsection

@section('admin-content')
	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Create Permission</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Permission</li>
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
                    <h3 class="card-title">Permission Information</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="form-create" action="" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Group Name</label>
                            <input id="name" type="name" name="group_name" class="form-control" value="" placeholder="admin">
                            <span id="errors-group_name" class="error text-danger hidden"></span>
                        </div>
                        <div class="form-group">
                            <label>Permission</label><br>
                                <input id="checkAll" type="checkbox"> 
                                <label>All</label>   
                                <hr>

                                <input type="checkbox" class="checkItem" name="name[]" value="create">
                                <label class="margin-label">Create</label>

                                <input type="checkbox" class="checkItem" name="name[]" value="edit">
                                <label class="margin-label">Edit</label>

                                <input type="checkbox" class="checkItem" name="name[]" value="delete">
                                <label class="margin-label">Delete</label>

                                <input type="checkbox" class="checkItem" name="name[]" value="view">
                                <label>View</label><br>
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
        // If checkbox Item full then prop true checkbox All else prop false checkbox All
        $('.checkItem').click(function(event) {
            const checkedItemTotal = $('input[name="name[]"]:checked').length;
            const checkItemTotal   = $('input[name="name[]"]').length;
            propCheckAll(checkedItemTotal, checkItemTotal); // admin/layouts/partials/script.blade.php
        });
        
        
        // Create permission by ajax
        const idForm = 'form-create';
        $("#" + idForm).submit(function(event) {
            event.preventDefault();
            const data = $(this).serialize();

            $.ajax({
                url: '{{ route('admin.role_permission.permission.store') }}',
                type: 'POST',
                data: data,
                success: function(response){
                    if (response.error) {
                        // Show error message and fade it
                        showAndFadeErrorText('#errors-name', response.messages.name);
                        showAndFadeErrorText('#errors-group_name', response.messages.group_name);
                    }else{
                        alertAndResetForm(response.messages, idForm);                              
                    }
                }
            });
        });
    </script>
@endpush