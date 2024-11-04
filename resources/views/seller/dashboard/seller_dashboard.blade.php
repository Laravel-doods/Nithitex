@extends('seller.seller_main_master')
@section('content')
@section('title')
    Dashboard | India's No 1 Online Saree Shop - Nithitex
@endsection
@php
    $id = Auth::user()->id;
    $user = App\Models\User::find($id);
    $coupon = App\Models\User::join('coupons', 'coupons.id', 'users.coupon_id')
        ->where('users.id', Auth::user()->id)
        ->first();
@endphp

<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{ route('seller.home') }}">Home</a>
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
                        @include('seller.seller_sidebar')
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
                                                Logout</a>)
                                        </p>
                                        <p class="mb-0">Welcome to Nithi Tex | India's No 1 Online Saree Shop</p>
                                    </div>
                                    <div>
                                        <img src="{{ asset('frontend/assets/images/img_seller.jpg') }}">
                                    </div>
                                </div>
                                @if ($coupon)
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h3 class="badge text-dark p-2"
                                                style="background-color: {{ $coupon->color_code }}">
                                                {{ $coupon->coupon_name }}</h3>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="d-flex justify-content-center">
                                                <div class="d-flex mx-2"> <strong>Your Coupon Code</strong></div>
                                                <div class="d-flex"> <input type="text" name="txtCouponCode"
                                                        id="txtCouponCode" value="{{ $coupon->coupon_code }}">
                                                    <button type="button" onclick="copyCoupon()"  class="btn btn-primary"><span
                                                            class="fa-sharp fa-regular fa-copy badge"><i
                                                                class=""></i></span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-12 col-xl-12 stretch-card">
                                        <div class="row flex-grow">
                                            <div class="col-md-3 grid-margin stretch-card mt-3">
                                                <div class="card">
                                                    <div class="card-body bg-warning text-center">
                                                        <div class="d-flex justify-content-center align-items-baseline">
                                                            <h4 class="card-title mb-0">All Orders</h4>
                                                        </div>
                                                        @php
                                                            $allorders = App\Models\Order::where('user_id', Auth::user()->id)
                                                                // ->where('userrole_id', 2)
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
                                            <div class="col-md-3 grid-margin stretch-card  mt-3">
                                                <div class="card">
                                                    <div class="card-body bg-info text-center">
                                                        <div class="d-flex justify-content-center align-items-baseline">
                                                            <h4 class="card-title mb-0">Return Orders</h4>
                                                        </div>
                                                        @php
                                                            $returnorders = App\Models\Order::where('status', 'returned')
                                                                // ->where('userrole_id', 2)
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
                                                                // ->where('userrole_id', 2)
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
                                            <div class="col-md-3 grid-margin stretch-card mt-3">

                                            </div>
                                            {{-- <div class="row mt-5"> --}}
                                            <div class="col-md-3 grid-margin stretch-card mt-3">
                                                <div class="card">
                                                    <div class="card-body bg-primary text-center">
                                                        <div class="d-flex justify-content-center align-items-baseline">
                                                            <h4 class="card-title mb-0">Margin Earned</h4>
                                                        </div>
                                                        @php
                                                            $margin_earned = App\Models\Order::where('user_id', Auth::user()->id)
                                                                ->where('return_order', '!=', 2)
                                                                ->where('cancel_request', '!=', 2)
                                                                ->where('status', '!=', 'cancelled')
                                                                ->sum('margin_amount');
                                                        @endphp
                                                        <div class="row">
                                                            <div class="col-12 col-md-12 col-xl-12">
                                                                <h2 class="mb-2">₹{{ $margin_earned }}</h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 grid-margin stretch-card mt-3">
                                                <div class="card">
                                                    <div class="card-body bg-success text-center">
                                                        <div class="d-flex justify-content-center align-items-baseline">
                                                            <h4 class="card-title mb-0">Received</h4>
                                                        </div>
                                                        @php
                                                            $received = App\Models\Order::where('user_id', Auth::user()->id)
                                                                ->where('payment_status', 'paid')
                                                                ->where('return_order', '!=', 2)
                                                                ->where('cancel_request', '!=', 2)
                                                                ->where('status', '!=', 'cancelled')
                                                                ->sum('margin_amount');
                                                        @endphp
                                                        <div class="row">
                                                            <div class="col-12 col-md-12 col-xl-12">
                                                                <h2 class="mb-2">₹{{ $received }}</h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 grid-margin stretch-card mt-3">
                                                <div class="card">
                                                    <div class="card-body bg-warning text-center">
                                                        <div class="d-flex justify-content-center align-items-baseline">
                                                            <h4 class="card-title mb-0">Outstanding</h4>
                                                        </div>
                                                        @php
                                                            $pending = App\Models\Order::where('user_id', Auth::user()->id)
                                                                ->where('payment_status', 'unpaid')
                                                                ->where('return_order', '!=', 2)
                                                                ->where('cancel_request', '!=', 2)
                                                                ->where('status', '!=', 'cancelled')
                                                                ->sum('margin_amount');
                                                        @endphp
                                                        <div class="row">
                                                            <div class="col-12 col-md-12 col-xl-12">
                                                                <h2 class="mb-2">₹{{ $pending }}</h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- </div> --}}
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
<script>
    function copyCoupon() {
        // Get the text field
        var copyText = document.getElementById("txtCouponCode");

        // Select the text field
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices

        // Copy the text inside the text field
        navigator.clipboard.writeText(copyText.value);

        // Alert the copied text
        alert("Copied the text: " + copyText.value);
    }
</script>
