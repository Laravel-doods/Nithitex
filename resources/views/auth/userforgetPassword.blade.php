@extends('frontend.main_master')
@section('content')
@section('title')
India's No 1 Online Saree Shop - Nithitex
@endsection
        <div class="login-register-area pt-80 pb-80">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-12 ms-auto me-auto">
                        <div class="login-register-wrapper">
                            <div class="login-register-tab-list nav">
                                <a class="active" data-bs-toggle="tab" href="#lg1">
                                    <h4> Forgot Password </h4>
                                </a>
                            </div>
                            <div class="tab-content">
                                <div id="lg1" class="tab-pane active">
                                    <div class="login-form-container">
                                        <div class="login-register-form">
                                            @if(count($errors) > 0 )
                                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <ul class="p-0 m-0" style="list-style: none;">
                                                        @foreach($errors->all() as $error)
                                                        <li>{{$error}}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <form action="{{ route('user.forget.password.post') }}" method="POST">
                                                @csrf
                                                <input type="email" id="email" class="form-control" placeholder="Enter your email" name="email" required autofocus autocomplete="off">
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <button type="submit" class="btn btn-primary">
                                                    Send Password Reset Link
                                                </button>
                                                </div>
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

@endsection
