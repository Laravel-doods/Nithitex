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
Privacy Policy | India's No 1 Online Saree Shop - Nithitex
@endsection
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">Privacy Policy</li>
            </ul>
        </div>
    </div>
</div>
<div class="about-us-area pt-120 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="privacy-content">
                    <h2>Privacy Policy</h2>
                    @foreach ($privacyPolicy as $item)
                    <p>{!!$item->privacy_policy!!}</p>
                    @endforeach
                 </div>
            </div>
        </div>
    </div>
</div>
@endsection