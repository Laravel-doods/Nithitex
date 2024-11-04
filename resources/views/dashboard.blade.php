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
                <li class="active">My Account </li>
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
                        <div class="col-lg-10 col-md-10">
                            <div class="myaccount-content">
                                <h3>Dashboard</h3>
                                <div class="d-flex justify-content-between">
                                    <div class="welcome">
                                        <p>Hello, <strong>{{ Auth::user()->name }}</strong> (If Not
                                            <strong>{{ Auth::user()->name }} !</strong><a
                                                href="{{ route('user.logout') }}" class="logout"> Proceed to
                                                Logout</a>)</p>
                                        <p class="mb-0">Welcome to Nithi Tex | India's No 1 Online Saree Shop</p>
                                    </div>
                                </div>
                                <div class="row mt-50">
                                    <div class="col-12 col-xl-12 stretch-card">
                                        <div class="row flex-grow">
                                            <div class="col-md-3 grid-margin stretch-card  mt-3">
                                                <div class="card">
                                                    <div class="card-body bg-warning text-center">
                                                        <div class="d-flex justify-content-center align-items-baseline">
                                                            <h4 class="card-title mb-0">All Orders</h4>
                                                        </div>
                                                        @php
                                                            $allorders = App\Models\Order::where('user_id', Auth::user()->id)
                                                                // ->where('userrole_id', 1)
                                                                ->get()
                                                                ->count();
                                                        @endphp
                                                        <div class="row">
                                                            <div class="col-12 col-md-12 col-xl-12">
                                                                <h2 class="mb-2">{{ $allorders }}</h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 grid-margin stretch-card mt-3">
                                                <div class="card">
                                                    <div class="card-body bg-info text-center">
                                                        <div class="d-flex justify-content-center align-items-baseline">
                                                            <h4 class="card-title mb-0">Return Orders</h4>
                                                        </div>
                                                        @php
                                                            $returnorders = App\Models\Order::where('status', 'returned')
                                                                // ->where('userrole_id', 1)
                                                                ->where('user_id', Auth::user()->id)
                                                                ->get()
                                                                ->count();
                                                        @endphp
                                                        <div class="row">
                                                            <div class="col-12 col-md-12 col-xl-12">
                                                                <h2 class="mb-2">{{ $returnorders }}</h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 grid-margin stretch-card  mt-3">
                                                <div class="card">
                                                    <div class="card-body bg-danger text-center">
                                                        <div class="d-flex justify-content-center align-items-baseline">
                                                            <h4 class="card-title mb-0">Cancel Orders</h4>
                                                        </div>
                                                        @php
                                                            $cancelorders = App\Models\Order::where('status', 'cancelled')
                                                                // ->where('userrole_id', 1)
                                                                ->where('user_id', Auth::user()->id)
                                                                ->get()
                                                                ->count();
                                                        @endphp
                                                        <div class="row">
                                                            <div class="col-12 col-md-12 col-xl-12">
                                                                <h2 class="mb-2">{{ $cancelorders }}</h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- row -->
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
