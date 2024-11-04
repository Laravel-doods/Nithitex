@extends('frontend.main_master')
@section('content')
@section('title')
    India's No 1 Online Saree Shop - Nithitex
@endsection
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">My Cart </li>
            </ul>
        </div>
    </div>
</div>
<div class="cart-main-area pt-50 pb-50">
    <div class="container mycart hidden">
        <h3 class="cart-page-title">Cart</h3>
        <div class="row">
            <div class="col-md-9">
                <div class="table-responsive">
                    <table class="table table-stripped scroll-bar">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="cartPage">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-3">
                <div class="grand-totall">
                    <h4 class="grand-totall-title">Cart Total<span id="cartSubTotal"></span></h4>
                    <a href="{{ route('checkout') }}">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>
    <div id="emptycart" class="hidden">
        <div class="d-flex justify-content-center align-items-center">
            <img src="{{ asset('frontend/assets/images/cart/empty_cart.png') }}">
        </div>
    </div>
</div>
@endsection
