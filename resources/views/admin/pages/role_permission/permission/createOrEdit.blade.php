@extends('admin.pages.master')

@section('admin-title')
    Permission - {{ isset($permission) ? ' Edit '. $permission->group_name : ' Create ' }} Permission
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
                        <h1 class="m-0">{{ isset($permission) ? ' Edit '. $permission->group_name : ' Create ' }} Permission</h1>
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
                <form id="form-createOrEdit" action="" method="POST">
                    @isset($permission)
                        @method('PUT')
                    @endisset

                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Group Name</label>
                            <input id="name" type="name" {{ isset($permission) ? 'readonly' : '' }} name="group_name" class="form-control" 
                                value="{{ isset($permission) ? old('group_name', $permission->group_name) : '' }}" 
                            placeholder="admin">
                            <span id="errors-group_name" class="error text-danger hidden"></span>
                        </div>

                        @isset($permission)
                            @php 
                                $permission_name = json_encode(permissionNamesByGroupBy($permission));
                            @endphp 
                        @endisset

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

        /* Show checkbox permission if group name has permission
        ** ========================================================================
        */
            if ($('input[name="_method"]').val() == 'PUT') {
                // Get element input value to array 
                const dataValueInput = [];
                $('input[name="name[]"]').each(function() {
                    dataValueInput.push($(this).val());
                });

                // Get data permission name
                const permissionName = JSON.parse('<?php echo isset($permission_name) ? $permission_name  : '' ?>');

                // Diff 2 array and get same value
                const arrayDiff = permissionName.filter(value => dataValueInput.includes(value));
                
                switch (arrayDiff.length) {
                    case 4:
                        $('#checkAll').prop('checked', true);
                        $('.checkItem').prop('checked', true);
                        break;
                    default:
                        for (var i = 0; i < permissionName.length; i++) {
                            $('input[name="name[]"]').each(function() {
                                if ($(this).val() == permissionName[i]) {
                                    $(this).prop('checked', true);
                                }
                            })
                        }
                        break;
                }
            }
            
        /*
        ** ========================================================================
        */

        function ajaxCreateOrUdate(url, method, data, idForm) {
            $.ajax({
                url: url,
                type: method,
                data: data,
                success: function(response){
                    if (response.error) {
                        // Show error message and fade it
                        showAndFadeErrorText('#errors-name', response.messages.name);
                        showAndFadeErrorText('#errors-group_name', response.messages.group_name);
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

        const url    = '{{ (isset($permission)) ? route('admin.role_permission.permission.update', $permission->group_name) : route('admin.role_permission.permission.store') }}';
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