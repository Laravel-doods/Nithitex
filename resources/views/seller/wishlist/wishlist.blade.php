@extends('seller.seller_main_master')
@section('content')
@section('title')
    India's No 1 Online Saree Shop - Nithitex
@endsection
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{ route('seller.home') }}">Home</a>
                </li>
                <li class="active">Wishlist </li>
            </ul>
        </div>
    </div>
</div>
<div class="cart-main-area pt-115 pb-120">
    <div class="container mywishlist hidden">
        <h3 class="cart-page-title">Wishlist</h3>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="table-responsive">
                    <table class="table table-stripped scroll-bar">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="wishlist">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="emptywishlist" class="hidden">
        <div class="d-flex justify-content-center align-items-center">
            <img src="{{ asset('frontend/assets/images/cart/empty_wishlist.png') }}">
        </div>
    </div>
</div>
@endsection
