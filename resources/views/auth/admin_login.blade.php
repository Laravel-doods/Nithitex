<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Admin Login | India's No 1 Online Saree Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Nithitex - India's No 1 Online Saree Shop - Nithitex" name="description" />
    <meta content="Nithitex" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/core/core.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/demo_1/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
</head>
<style>
    .field-icon {
        float: right;
        margin-right: 10px;
        margin-top: -30px;
        position: relative;
        z-index: 2;
        font-size: 20px
    }
    @media only screen and (min-width: 992px) and (max-width: 1199px) {
        .auth-left-wrapper {
            display: none !important;
        }
    }

    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .auth-left-wrapper {
            display: none !important;
        }
    }

    @media only screen and (max-width: 767px) {
        .auth-left-wrapper {
            display: none !important;
        }
    }
</style>

<body class="sidebar-dark bg-secondary">
    <div class="main-wrapper">
        <div class="page-wrapper full-page">

            <div class="page-content d-flex align-items-center justify-content-center">

                <div class="row w-100 mx-0 auth-page">
                    <div class="col-md-8 col-xl-6 mx-auto">
                        <h4 class="text-center text-primary my-3">Nithitex Admin Login</h4>
                        <div class="card">
                            <div class="row">
                                <div class="col-md-4 pr-md-0">
                                    <div class="auth-left-wrapper">
                                        <img src="{{ asset('backend/assets/images/nithi-admin-login.png') }}"
                                            class="img-responsive" alt="">
                                    </div>
                                </div>
                                <div class="col-md-8 pl-md-0">
                                    <div class="auth-form-wrapper px-4 py-5">
                                        <a href="/" class="noble-ui-logo d-block mb-2"><img
                                                src="{{ asset('frontend/assets/images/logo/logo.png') }}" height="70"
                                                class="logo-light mx-auto" alt=""></a>
                                        <h5 class="text-muted font-weight-normal mb-4">Welcome back Admin! Log in to
                                            your account.</h5>
                                        <form method="POST" action="{{ route('admin.login') }}">
                                            @csrf
                                            <input type="hidden" name="fcm_token" id="fcm_token" value="">
                                            <div class="form-group">
                                                <label for="email">Email address</label>
                                                <input class="form-control" type="email" required=""
                                                    placeholder="E-Mail" id="email" name="email">
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input id="password-field" type="password" class="form-control"
                                                    placeholder="Password" name="password">
                                                <span toggle="#password-field"
                                                    class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                            </div>
                                            <div class="form-check form-check-flat form-check-primary">
                                                <button type="submit"
                                                    class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">Log
                                                    In</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="{{ asset('backend/assets/vendors/core/core.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/core/core.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/template.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(".toggle-password").click(function() {

            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    </script>
    <script>
        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}"
            switch (type) {
                case 'info':
                    toastr.info(" {{ Session::get('message') }} ");
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    break;
                case 'success':
                    toastr.success(" {{ Session::get('message') }} ");
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    break;
                case 'warning':
                    toastr.warning(" {{ Session::get('message') }} ");
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    break;
                case 'error':
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.error(" {{ Session::get('message') }} ");
                    break;
            }
        @endif
    </script>
</body>

</html>
