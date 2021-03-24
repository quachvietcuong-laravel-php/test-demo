<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Login - Admin</title>
    <base href="{{ asset('') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="admin_asset/auth_asset/images/icon/favicon.ico">
    <link rel="stylesheet" href="admin_asset/auth_asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_asset/auth_asset/css/font-awesome.min.css">
    <link rel="stylesheet" href="admin_asset/auth_asset/css/themify-icons.css">
    <link rel="stylesheet" href="admin_asset/auth_asset/css/metisMenu.css">
    <link rel="stylesheet" href="admin_asset/auth_asset/css/owl.carousel.min.css">
    <link rel="stylesheet" href="admin_asset/auth_asset/css/slicknav.min.css">
    <!-- amchart css -->
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <!-- others css -->
    <link rel="stylesheet" href="admin_asset/auth_asset/css/typography.css">
    <link rel="stylesheet" href="admin_asset/auth_asset/css/default-css.css">
    <link rel="stylesheet" href="admin_asset/auth_asset/css/styles.css">
    <link rel="stylesheet" href="admin_asset/auth_asset/css/responsive.css">
    <!-- modernizr css -->
    <script src="admin_asset/auth_asset/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- preloader area start -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->
    <!-- login area start -->
    <div class="login-area">
        <div class="container">
            <div class="login-box ptb--100">
                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf
                    <div class="login-form-head">
                        <h4>Sign In</h4>
                        <p>Hello there, Sign in and start managing your Admin Template</p>
                    </div>
                    <div class="login-form-body">
                        <div class="form-gp">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" name="email" id="exampleInputEmail1">
                            <i class="ti-email"></i>
                            <div class="text-danger"></div>
                        </div>
                        <div class="form-gp">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" name="password" id="exampleInputPassword1">
                            <i class="ti-lock"></i>
                            <div class="text-danger"></div>
                        </div>
                        <div class="row mb-4 rmber-area">
                            <div class="col-6">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="customControlAutosizing">
                                    <label class="custom-control-label" for="customControlAutosizing">Remember Me</label>
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <a href="#">Forgot Password?</a>
                            </div>
                        </div>
                        <div class="submit-btn-area">
                            <button id="form_submit" type="submit">Submit <i class="ti-arrow-right"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- login area end -->

    <!-- jquery latest version -->
    <script src="admin_asset/auth_asset/js/vendor/jquery-2.2.4.min.js"></script>
    <!-- bootstrap 4 js -->
    <script src="admin_asset/auth_asset/js/popper.min.js"></script>
    <script src="admin_asset/auth_asset/js/bootstrap.min.js"></script>
    <script src="admin_asset/auth_asset/js/owl.carousel.min.js"></script>
    <script src="admin_asset/auth_asset/js/metisMenu.min.js"></script>
    <script src="admin_asset/auth_asset/js/jquery.slimscroll.min.js"></script>
    <script src="admin_asset/auth_asset/js/jquery.slicknav.min.js"></script>
    
    <!-- others plugins -->
    <script src="admin_asset/auth_asset/js/plugins.js"></script>
    <script src="admin_asset/auth_asset/js/scripts.js"></script>
</body>

</html>