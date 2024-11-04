<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Reseller Login | Nithi Tex | India's No 1 Online Saree Shop</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesdesign" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('backend/assets/images/favicon.png')}}">

        <link rel="stylesheet" href="{{asset('backend/assets/vendors/core/core.css')}}">
        <link rel="stylesheet" href="{{asset('backend/assets/fonts/feather-font/css/iconfont.css')}}">
        <link rel="stylesheet" href="{{asset('backend/assets/css/demo_1/style.css')}}">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

    </head>
    <style>
      @media only screen and (min-width: 992px) and (max-width: 1199px) {
        .auth-left-wrapper{
        display: none !important;
        }
      }
      @media only screen and (min-width: 768px) and (max-width: 991px) {
        .auth-left-wrapper{
        display: none !important;
        }
      }
      @media only screen and (max-width: 767px) {
        .auth-left-wrapper{
        display: none !important;
        }
      }
    </style>

    <body class="sidebar-dark bg-secondary">
        <div class="main-wrapper">
            <div class="page-wrapper full-page">
          <h4 class="text-center text-primary mt-5">Reseller Login</h4>

                <div class="page-content d-flex align-items-center justify-content-center">
    
                    <div class="row w-100 mx-0 auth-page">
                        <div class="col-md-8 col-xl-6 mx-auto">
                            <div class="card">
                                <div class="row">
                    <div class="col-md-4 pr-md-0">
                      <div class="auth-left-wrapper">
                        <img src="{{asset('backend/assets/images/nithi-seller-login.png')}}" class="img-responsive" alt="">
                      </div>
                    </div>
                    <div class="col-md-8 pl-md-0">
                      <div class="auth-form-wrapper px-4 py-5">
                        <a href="/" class="noble-ui-logo d-block mb-2"><img src="{{asset('frontend/assets/images/logo/logo.png')}}" height="70" class="logo-light mx-auto" alt=""></a>
                        <h5 class="text-muted font-weight-normal mb-4">Welcome back! Log in to your account.</h5>
                        <form class="forms-sample" method="POST" action="{{ route('seller.store') }}">
                          @csrf
                          <div class="form-group">
                            <label for="email">Email address</label>
                            <input class="form-control" type="email" required="" placeholder="E-Mail" id="email" name="email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                          <div class="form-group">
                            <label for="password">Password</label>
                            <input id="password-field" type="password" class="form-control" placeholder="Password" name="password">
                            <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          </div>
                          {{-- <div class="form-check form-check-flat form-check-primary">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="remember">
                              Remember me
                            </label>
                          </div> --}}
                          {{-- <div class="mt-3">
                            <a href="{{ route('admin.login') }}" class="btn btn-primary mr-2 mb-2 mb-md-0 text-white">Login</a>
                            <button type="submit" class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                              <i class="btn-icon-prepend" data-feather="twitter"></i>
                              Login with twitter
                            </button>
                          </div> --}}
                          {{-- <a href="register.html" class="d-block mt-3 text-muted">Not a user? Sign up</a> --}}
                          <button type="submit" class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">Log In</button>
                        </form>
                        <a href="{{ route('seller.register') }}" class="d-block mt-3 text-muted">Not a Reseller? Register Here    <button type="button" class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">Register as Reseller</button>
                        </a>
                        <a href="{{ route('forget.password.get') }}">Forgot Password?</a>
                        </div>
                    </div>
                  </div>
                            </div>
                        </div>
                    </div>
    
                </div>
            </div>
        </div>
    
        <script src="{{asset('backend/assets/vendors/core/core.js')}}"></script>
        <script src="{{asset('backend/assets/vendors/core/core.js')}}"></script>
        <script src="{{asset('backend/assets/vendors/feather-icons/feather.min.js')}}"></script>
        <script src="{{asset('backend/assets/js/template.js')}}"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <script>
          @if(Session::has('message'))
          var type = "{{ Session::get('alert-type','info') }}"
          switch(type){
             case 'info':
             toastr.info(" {{ Session::get('message') }} ");
             break;
             case 'success':
             toastr.success(" {{ Session::get('message') }} ");
             break;
             case 'warning':
             toastr.warning(" {{ Session::get('message') }} ");
             break;
             case 'error':
             toastr.error(" {{ Session::get('message') }} ");
             break; 
          }
          @endif 
         </script>
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
      <style>
      .field-icon {
          float: right;
          margin-right: 10px;
          margin-top: -30px;
          position: relative;
          z-index: 2;
          font-size: 20px
      }
      </style>

    </body>
</html>
