@extends('frontend.main_master')
  
@section('content')
<main class="login-form">
        <div class="breadcrumb-area bg-gray">
            <div class="container">
                <div class="breadcrumb-content text-center">
                    <ul>
                        <li>
                            <a href="/">Home</a>
                        </li>
                        <li class="active">Reset Password</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="card mt-20 mb-20">
                    <div class="col-md-8">
                        <div class="card-header">Reset Password</div>
                        <div class="card-body">
        
                          @if (Session::has('message'))
                               <div class="alert alert-success" role="alert">
                                  {{ Session::get('message') }}
                              </div>
                          @endif
        
                            <form action="{{ route('forget.password.post') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                    <div class="col-md-6">
                                        <input type="text" id="email_address" class="form-control" name="email" required autofocus autocomplete="off">
                                        @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <button type="submit" class="btn btn-primary">
                            Send Password Reset Link
                        </button>
                    </div>
                </div>
            </form>
        </div>
        </div>
  </main>
@endsection