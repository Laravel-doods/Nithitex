@extends('frontend.main_master')
@section('content')
@section('title')
    Dashboard | India's No 1 Online Saree Shop - Nithitex
@endsection
@php
    $id = Auth::user()->id;
    $user = App\Models\User::find($id);
@endphp

<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">Change Password </li>
            </ul>
        </div>
    </div>
</div>
<!-- my account wrapper start -->
<div class="my-account-wrapper pt-120 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- My Account Page Start -->
                <div class="myaccount-page-wrapper">
                    <!-- My Account Tab Menu Start -->
                    <div class="row">
                        @include('frontend.common.user_sidebar')
                        <!-- My Account Tab Menu End -->
                        <!-- My Account Tab Content Start -->
                        <div class="col-lg-6 col-md-6">
                            <div class="myaccount-content">
                                <h3>Change Password</h3>
                                <form method="post" action="{{ route('user.password.update') }}"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group">
                                        <label class="info-title" for="exampleInputEmail1">Current Password <span>
                                            </span></label>
                                        <input type="password" id="current_password" name="oldpassword"
                                            class="form-control" autocomplete="off">
                                        <span toggle="#current_password"
                                            class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        @error('oldpassword')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="info-title" for="exampleInputEmail1">New Password <span>
                                            </span></label>
                                        <input type="password" id="password" name="password" class="form-control"
                                            autocomplete="off">
                                        <span toggle="#password"
                                            class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="info-title" for="exampleInputEmail1">Confirm Password <span>
                                            </span></label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control" autocomplete="off">
                                        <span toggle="#password_confirmation"
                                            class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        @error('password_confirmation')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group pt-2">
                                        <button type="submit" class="btn btn-danger">Update</button>
                                    </div>

                                </form>

                            </div>
                        </div> <!-- My Account Tab Content End -->
                    </div>
                </div> <!-- My Account Page End -->
            </div>
        </div>
    </div>
</div>
<!-- my account wrapper end -->
@endsection
