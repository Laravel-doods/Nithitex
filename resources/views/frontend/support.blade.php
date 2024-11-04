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
Support | India's No 1 Online Saree Shop - Nithitex
@endsection


<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">Support</li>
            </ul>
        </div>
    </div>
</div>
<div class="about-us-area pt-120 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="privacy-content">
                    <h2>Support</h2>
                    @foreach ($supportPolicy as $item)
                    <p>{!!$item->support_policy!!}</p>
                    @endforeach
                 </div>
            </div>
        </div>
    </div>
</div>

@endsection