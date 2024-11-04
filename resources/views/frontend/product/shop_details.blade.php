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
Shop - All Products | India's No 1 Online Saree Shop - Nithitex
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">Shop</li>
            </ul>
        </div>
    </div>
</div>
<div class="shop-area pt-20 pb-20">
    <div class="container">
        <div class="row flex-row-reverse">
            <div class="col-lg-9">
                <div class="shop-topbar-wrapper">
                    <div class="shop-topbar-left">
                        <div class="view-mode nav">
                            <a class="active" href="#shop-1" data-bs-toggle="tab"><i class="icon-grid"></i></a>
                            <a href="#shop-2" data-bs-toggle="tab"><i class="icon-menu"></i></a>
                        </div>
                        <p>Showing all products </p>
                    </div>
                    <div class="product-sorting-wrapper">
                        <form action="{{route(($seller_url == "" ? "" : $seller_url).'product.color.sort')}}" method="get">
                            <div class="product-show shorting-style">
                                <label>Sort by color :</label>
                                <select name="color_sort" id="color_sort">
                                    <option value="">select</option>
                                    @foreach ($color as $item)
                                        <option value="{{$item->id}}" @if($sel_color==$item->id) selected @endif>{{$item->color_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="product-sorting-wrapper">
                        <form action="{{route(($seller_url == "" ? "" : $seller_url).'product.sort')}}" method="get">
                            <div class="product-show shorting-style">
                                <label>Sort by :</label>
                                <select name="sort" id="sort">  
                                    <option value="" @if($sort_by=="") selected @endif>select</option>
                                    <option value="latest_product" @if($sort_by=="latest_product") selected @endif>Latest Product</option>
                                    <option value="product_name" @if($sort_by=="product_name") selected @endif>Product Name</option>
                                    <option value="p_low_to_high" @if($sort_by=="p_low_to_high") selected @endif>Price : Low To High</option>
                                    <option value="p_high_to_low" @if($sort_by=="p_high_to_low") selected @endif>Price : High To Low</option>
                                    <option value="q_low_to_high" @if($sort_by=="q_low_to_high") selected @endif>Quantity : Low To High</option>
                                    <option value="q_high_to_low" @if($sort_by=="q_high_to_low") selected @endif>Quantity : High To Low</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="shop-bottom-area">
                    <div class="tab-content jump">
                        <input type="hidden" value="{{ $offer ?? '' }}" id="offer">
                        <input type="hidden" id="product_count" value="{{ $shop_all_products->count() }}">
                        @if($shop_all_products -> isEmpty())
                            <div class="d-flex justify-content-center align-items-center">
                                <img src="{{asset("frontend/assets/images/bg/empty_results.png")}}">
                            </div>
                        @else
                            <div id="shop-1" class="tab-pane active">
                                <div class="row" id="grid_view_product">
                                    @include('frontend.product.shop_grid_view')
                                </div>
                            </div>
                            <div id="shop-2" class="tab-pane">
                                <div class="row" id="list_view_product">
                                    @include('frontend.product.shop_list_view')
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="ajax-loadmore-product text-center" style="display:none;">
                        <img src="{{ asset('frontend/assets/images/logo/Spinner-1s-200px.svg') }}" style="width: 120px; height: 120px;" alt="">
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                @include('frontend.product.sidebar')
            </div>
        </div>
    </div>
</div>

@endsection