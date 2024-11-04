@php
$url = "frontend.main_master";
$seller_url = "";
if(Auth::check()) {
    if (Auth::user()->userrole_id == 2) {
        $url = "seller.seller_main_master";
        $seller_url = "seller.";
    }
}
@endphp
@extends($url)
@section('content')
@section('title')
Track Order | India's No 1 Online Saree Shop - Nithitex
@endsection


<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">order tracking</li>
            </ul>
        </div>
    </div>
</div>
<!-- order tracking start -->
<div class="order-tracking-area pt-110 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-8 col-md-10 ms-auto me-auto">
                <div class="order-tracking-content">
                    <p>To track your order please enter your Track Number which was received to your mobile number in the box below and press the "Track Now" button.</p>
                    <div class="order-tracking-form">
                        <form method="post" action="{{ route('order.tracking') }}">
                            @csrf
                            <div class="sin-order-tracking">
                                <label>Track Number</label>
                                <input type="text" id="code" name="code" placeholder="Enter Your Track Number" autocomplete="off">
                            </div>
                            <div class="order-track-btn">
                                <button class="btn btn-danger" type="submit" style="margin-left: 17px;"> Track Now </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection