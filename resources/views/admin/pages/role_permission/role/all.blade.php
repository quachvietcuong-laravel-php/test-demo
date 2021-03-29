@extends('admin.pages.master')

@section('admin-title')
    Roles - All Role
@endsection

@section('styles')
    <!-- DataTables  -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">

    <script type="text/javascript">
        // If checkbox Item full then prop true checkbox All else prop false checkbox All
        function itemChecked(value) {
            const checkedItemTotal = $('input[name="checked[]"]:checked').length;
            const checkItemTotal   = $('input[name="checked[]"]').length;
            propCheckAll(checkedItemTotal, checkItemTotal); // admin/layouts/partials/script.blade.php
        }

        // Delete single
        function deleteSingle(id) {
            const url = '{{ url('admin/role_permission/roles') }}' + '/' + id;
            deleteSingleRecord(url, '#table-data'); // admin/layouts/partials/script.blade.php
        }
    </script>
@endsection

@section('admin-content')
	<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Roles List</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Roles</li>
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
    <section id="main-content" class="content">
        <div id="content-table" class="container-fluid">
            <form id="form-data" action="" method="POST">
                @csrf
                <table id="table-data" class="display">
                    <thead>
                        <tr>
                            <th class="text-center">
                                <input id="checkAll" type="checkbox" name="check">
                            </th>
                            <th>Sl</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
     
                    <a style="margin: 0px 10px 10px 0px" class="btn btn-primary" href="{{ route('admin.role_permission.role.create') }}" role="button">Create</a>
                    <button id="delete-manage" style="margin-bottom: 10px;" type="submit" class="btn btn-danger">Delete</button>
                </table>
            </form>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

@endsection

@push('scripts')
    <!-- DataTables  -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        $(document).ready( function () {
            // Datatables
            const table = $('#table-data').DataTable( {
                responsive: true,
                processing: true,
                serverSide: true,
                "pagingType": "full_numbers",
                ajax: {
                    url: '{{ route('admin.role_permission.role.index') }}',
                    method: 'GET',
                },
                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false },
                    { data: 'sl', name: 'sl' },
                    { data: 'name', name: 'name' },
                    { data: 'action', name: 'action', orderable: false },
                ],
            });

            // Delete manage
            $('#form-data').submit(function(event) {
                event.preventDefault();

                const url = '{{ route('admin.role_permission.role.delete') }}';
                deleteMultiRecord(url, '#table-data'); // admin/layouts/partials/script.blade.php        
            });
        });

    </script>
@endpush