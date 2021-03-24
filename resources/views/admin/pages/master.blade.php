@include('admin.layouts.header')
    <!-- Navbar -->
    @include('admin.layouts.nav')
    <!-- /.navbar -->
    <!-- Main Sidebar Container -->
    @include('admin.layouts.aside')
    <!-- Content Wrapper. Contains page content -->
    @yield('admin-content')
    <!-- /.content-wrapper -->
@include('admin.layouts.footer')
	          