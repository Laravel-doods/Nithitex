<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Reseller Register | Nithi Tex | India's No 1 Online Saree Shop</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesdesign" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('backend/assets/images/favicon.png')}}">

        <link rel="stylesheet" href="{{asset('backend/assets/vendors/core/core.css')}}">
        <link rel="stylesheet" href="{{asset('backend/assets/fonts/feather-font/css/iconfont.css')}}">
        <link rel="stylesheet" href="{{asset('backend/assets/css/demo_1/style.css')}}">
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
      .field-icon{
          float: right;
          margin-right: 10px;
          margin-top: -30px;
          position: relative;
          z-index: 2;
          font-size: 20px
      }
    </style>
    <body class="sidebar-dark bg-secondary">
        <div class="main-wrapper">
            <div class="page-wrapper full-page">       
              <img src="{{asset('frontend/assets/images/banner/bg_reseller.png')}}">
                <div class="page-content d-flex align-items-center justify-content-center">    
                    <div class="row w-100 mx-0 auth-page">
                        <div class="col-md-8 col-xl-8 mx-auto">
                            <div class="card">
                              <h4 class="text-center text-primary my-3">Reseller Registration</h4>
                              <div class="alert alert-danger mx-4" role="alert">
                                Your registration will be approved with in 24 hours by Nithi Tex verification team.
                              </div>
                              <form class="forms-sample" method="POST" action="{{ route('seller.register.store')}}">
                                @csrf
                                  <div class="auth-form-wrapper px-4 py-4">                       
                                    <div class="row">                                  
                                        <div class="col-md-6">                                                 
                                          <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" id="name" value="{{old('name')}}" class="form-control @error('name') is-invalid @enderror" placeholder="Username" required autocomplete="off">
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                          </div>
                                          <div class="form-group">
                                            <label for="shopname">Shop Name</label>
                                            <input type="text" name="shopname" value="{{old('shopname')}}" id="shopname" class="form-control @error('shopname') is-invalid @enderror" placeholder="Shop Name" required autocomplete="off">
                                            @error('shopname')
                                              <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                          </div>
                                          <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="text" name="phone" value="{{old('phone')}}" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Mobile number" required autocomplete="off">
                                            @error('phone')
                                              <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                          </div>
                                          <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" value="{{old('email')}}" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="E-mail" required autocomplete="off">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                          </div>
                                          <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" name="password" value="{{old('password')}}" id="password-field" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required autocomplete="off">
                                            <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                          </div>
                                          <div class="form-group">
                                            <label for="password">Password Confirmation</label>
                                            <input type="password" id="password_confirmation" name="password_confirmation" value="{{old('password')}}" class="form-control @error('password') is-invalid @enderror" id="password-field" placeholder="Password confirmation" required autocomplete="off">
                                            <span toggle="#password_confirmation" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="shopname">Bank Name</label>
                                            <input type="text" name="bank_name" value="{{old('bank_name')}}" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" placeholder="Bank Name" required autocomplete="off">
                                            @error('bank_name')
                                              <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                          </div>
                                          <div class="form-group">
                                            <label for="holdername">Account Holder Name</label>
                                            <input type="text" name="holdername" value="{{old('holdername')}}" id="holdername" class="form-control @error('shopname') is-invalid @enderror" placeholder="Account Holder" required autocomplete="off">
                                            @error('holdername')
                                              <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                          </div>
                                          <div class="form-group">
                                            <label for="accountnumber">Account Number</label>
                                            <input type="text" name="accountnumber" value="{{old('accountnumber')}}" id="accountnumber" class="form-control @error('accountnumber') is-invalid @enderror" placeholder="Account Number" required autocomplete="off">
                                            @error('accountnumber')
                                              <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                          </div>
                                          <div class="form-group">
                                            <label for="ifsccode">IFSC Code</label>
                                            <input type="text" name="ifsccode" value="{{old('ifsccode')}}" id="ifsccode" class="form-control @error('ifsccode') is-invalid @enderror" placeholder="IFSC Code" required autocomplete="off">
                                            @error('ifsccode')
                                              <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                          </div>
                                          
                                          </div>
                                    </div>
                                    <button type="submit" class="btn btn-warning btn-icon-text mb-2 mb-md-0">Register</button>
                                  </div>
                              </form>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    
        <script src="{{asset('backend/assets/vendors/core/core.js')}}"></script>
        <script src="{{asset('backend/assets/vendors/feather-icons/feather.min.js')}}"></script>
        <script src="{{asset('backend/assets/js/template.js')}}"></script>
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

    </body>
</html>
