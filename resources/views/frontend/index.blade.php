@extends('frontend.main_master')
@section('content')
@section('title')
    India's No 1 Online Saree Shop - Nithitex
@endsection
<div class="container-full banner-slider">
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach ($slider as $key => $item)
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{ $key }}"
                    @if ($key == 0) class="active" @endif aria-current="true"
                    aria-label="Slide {{ $key }}"></button>
                {{-- <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3"
                    aria-label="Slide 4"></button> --}}
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach ($slider as $key => $item)
                <div class="@if ($key == 0) carousel-item active @else carousel-item @endif">
                    <a href="{{ $item->web_redirect_url }}"><img src="{{ asset($item->slider_image) }}"
                            class="d-block w-100" alt="banner"></a>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<div class="container border-bottom-1">
    <div class="row about-us-img justify-content-center">
        @foreach ($main_category as $item)
            <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 col-3">
                <div class="text-center my-3">
                    <a href="{{ url('maincategory/product/' . $item->id) }}">
                        <img src="{{ asset($item->main_category_image) }}" alt=""
                            class="img-fluid imgmaincategory">
                        <div class="about-us-content pt-1 ">
                            <h5>{{ $item->main_category_name }}</h5>
                        </div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="about-us-area pt-40 about-us-img">
    <div class="container-fluid d-flex align-items-center justify-content-center">
        <div class="border-bottom-1 pb-40">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 col-12">
                    <div class="about-us-content my-3">
                        <h2>Types of Products</h2>
                    </div>
                    <div class="row about-us-img">
                        @foreach ($category as $item)
                            <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 col-6">
                                <div class="text-center my-3">
                                    <a href="{{ url('category/product/' . $item->id) }}"><img
                                            src="{{ asset($item->category_image) }}" alt=""
                                            class="img-fluid imgcategory">
                                        <div class="about-us-content">
                                            <h4>{{ $item->category_name }}</h4>
                                            <p>{{ $item->category_description }}</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="product-area section-padding-1 pt-50 pb-50">
    <div class="container-fluid">
        <div class="section-title pb-50">
            <h2>Featured Products</h2>
        </div>

        <div class="row">
            @foreach ($is_featured as $product)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-6">
                    <div class="single-product-wrap mb-35">
                        <div class="product-img product-img-zoom mb-20">
                            <a href="{{ url('product/details/' . $product->id . '/' . $product->product_slug) }}">
                                <img src="{{ asset($product->product_image) }}" alt="" class="img-fluid">
                            </a>
                            @if ($product->product_discount != null)
                                @php
                                    $amount = $product->product_price - $product->product_discount;
                                    $discount = ($amount / $product->product_price) * 100;
                                @endphp
                                <span class="pro-badge right bg-red"> {{ round($discount) }}%</span>
                            @endif
                            @if ($product->current_stock > 0)
                                <div class="product-action-wrap">
                                    <div class="product-action-left">
                                        <button type="submit" id="{{ $product->id }}"
                                            onclick="addToCartsimple(this.id, null)"><i class="icon-basket-loaded"></i>Add to Cart
                                        </button>
                                    </div>
                                    <div class="product-action-right tooltip-style">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                            id="{{ $product->id }}" onclick="productView(this.id)">
                                            <i class="icon-size-fullscreen icons"></i>
                                            <span>Quick View</span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                            
                        </div>
                        <div class="product-content-wrap">
                            <div class="product-content-left">
                                <h4>
                                    <a
                                        href="{{ url('product/details/' . $product->id . '/' . $product->product_slug) }}">
                                        {{ $product->product_name }}</a>
                                </h4>

                                @if ($product->product_discount == 0.0)
                                    <div class="product-price">
                                        <span class="new-price">₹{{ round($product->product_price) }}</span>
                                    </div>
                                @else
                                    <div class="product-price">
                                        <span class="new-price">₹{{ round($product->product_discount) }}</span>
                                        <span class="old-price">₹{{ round($product->product_price) }}</span>
                                    </div>
                                @endif
                            </div>
                            @if ($product->current_stock > 0)
                                <a href="{{ url('product/buynow/' . $product->id) }}" class="btn btn-primary mt-1 mb-1">Buy
                                    Now </a>
                            @else
                                <p class="text-danger mt-2">Currently unavailable</p>
                            @endif
                            <div class="product-content-right tooltip-style">
                                <button class="font-inc">
                                    <i class="@if (isset($product->is_favourite)) fa-solid fa-heart heart @else icon-heart @endif"
                                        id="{{ $product->id }}" onclick="addToWishList(this.id)"></i>
                                    <span>Wishlist</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-lg-12 pb-2">
                <a href="{{ route('product.shop') }}"><button type="button" class="btn btn-danger"
                        style="float: right;">View All</button></a>
            </div>
        </div>
    </div>
</div>

<div class="banner-area hidden">
    <div class="container-fluid d-flex align-items-center justify-content-center">
        <div class="row">
            @foreach ($about as $item)
                <div class="col-lg-6 col-md-6">
                    <div class="section-title">
                        <h2>About Us</h2>
                        <p>{{ $item->about_description }}</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <img src="{{ asset($item->about_image) }}" class="img-responsive" alt="brand-logo">
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="product-area section-padding-1 pt-50 pb-50">
    <div class="container-fluid">
        <div class="section-title pb-50">
            <h2>New Arrivals</h2>
        </div>

        <div class="row">
            @foreach ($newarrivals as $newarrival)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-6">
                    <div class="single-product-wrap mb-35">
                        <div class="product-img product-img-zoom mb-20">
                            <a
                                href="{{ url('product/details/' . $newarrival->id . '/' . $newarrival->product_slug) }}">
                                <img src="{{ asset($newarrival->product_image) }}" alt="" class="img-fluid">
                            </a>
                            @if ($newarrival->product_discount != null)
                                @php
                                    $amount = $newarrival->product_price - $newarrival->product_discount;
                                    $discount = ($amount / $newarrival->product_price) * 100;
                                @endphp
                                <span class="pro-badge right bg-red"> {{ round($discount) }}%</span>
                            @endif
                            @if ($product->current_stock > 0)
                                <div class="product-action-wrap">
                                    <div class="product-action-left">
                                        <input type="hidden" id="product_id" value="{{ $newarrival->id }}">
                                        <span id="pname" hidden>{{ $newarrival->product_name }}</span>
                                        <input type="hidden" id="qty" value="1">
                                        <button type="submit" id="{{ $newarrival->id }}"
                                            onclick="addToCartsimple(this.id, null)">
                                            <i class="icon-basket-loaded"></i>Add to Cart </button>
                                    </div>
                                    <div class="product-action-right tooltip-style">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                            id="{{ $newarrival->id }}" onclick="productView(this.id)">

                                            <i class="icon-size-fullscreen icons"></i>
                                            <span>Quick View</span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="product-content-wrap">
                            <div class="product-content-left">
                                <h4>
                                    <a
                                        href="{{ url('product/details/' . $newarrival->id . '/' . $newarrival->product_slug) }}">{{ $newarrival->product_name }}</a>
                                </h4>
                                @if ($newarrival->product_discount == 0.0)
                                    <div class="product-price">
                                        <span class="new-price">₹{{ round($newarrival->product_price) }}</span>
                                    </div>
                                @else
                                    <div class="product-price">
                                        <span class="new-price">₹{{ round($newarrival->product_discount) }}</span>
                                        <span class="old-price">₹{{ round($newarrival->product_price) }}</span>
                                    </div>
                                @endif
                            </div>
                            @if ($product->current_stock > 0)
                                <a href="{{ url('product/buynow/' . $newarrival->id) }}"
                                    class="btn btn-primary mt-1 mb-1">Buy Now </a>
                            @else
                                <p class="text-danger mt-2">Currently unavailable</p>
                            @endif
                            <div class="product-content-right tooltip-style">
                                <button class="font-inc">
                                    <i class="@if (isset($newarrival->is_favourite)) fa-solid fa-heart heart @else icon-heart @endif"
                                        id="{{ $newarrival->id }}" onclick="addToWishList(this.id)"></i>
                                    <span>Wishlist</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
</div>
</div>

<div class="container-full">
    <div class="d-flex align-items-center justify-content-center">
        <a href="{{ route('product.offers') }}">
            <img class="img-fluid" src="{{ asset('frontend/assets/images/banner/bg_offers.png') }}" alt="">
        </a>
    </div>
</div>

<div class="product-area section-padding-1 pt-50 pb-50">
    <div class="container-fluid">
        <div class="section-title pb-50">
            <h2>Best Selling</h2>
        </div>

        <div class="row">
            @foreach ($best_selling_products as $best_selling)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-6">
                    <div class="single-product-wrap mb-35">
                        <div class="product-img product-img-zoom mb-20">
                            <a
                                href="{{ url('product/details/' . $best_selling->id . '/' . $best_selling->product_slug) }}">
                                <img src="{{ asset($best_selling->product_image) }}" alt=""
                                    class="img-fluid">
                            </a>
                            @if ($best_selling->product_discount != null)
                                @php
                                    $amount = $best_selling->product_price - $best_selling->product_discount;
                                    $discount = ($amount / $best_selling->product_price) * 100;
                                @endphp
                                <span class="pro-badge right bg-red"> {{ round($discount) }}%</span>
                            @endif
                            @if ($product->current_stock > 0)
                                <div class="product-action-wrap">
                                    <div class="product-action-left">
                                        <input type="hidden" id="product_id" value="{{ $best_selling->id }}">
                                        <span id="pname" hidden>{{ $best_selling->product_name }}</span>
                                        <input type="hidden" id="qty" value="1">
                                        <button type="submit" id="{{ $best_selling->id }}"
                                            onclick="addToCartsimple(this.id, null)">
                                            <i class="icon-basket-loaded"></i>Add to Cart </button>
                                    </div>
                                    <div class="product-action-right tooltip-style">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                            id="{{ $best_selling->id }}" onclick="productView(this.id)">
                                            <i class="icon-size-fullscreen icons"></i>
                                            <span>Quick View</span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="product-content-wrap">
                            <div class="product-content-left">
                                <h4>
                                    <a
                                        href="{{ url('product/details/' . $best_selling->id . '/' . $best_selling->product_slug) }}">{{ $best_selling->product_name }}</a>
                                </h4>
                                <div class="product-price">
                                    <span class="new-price">₹{{ round($best_selling->product_discount) }}</span>
                                    <span class="old-price">₹{{ round($best_selling->product_price) }}</span>
                                </div>
                            </div>
                            @if ($product->current_stock > 0)
                                <a href="{{ url('product/buynow/' . $best_selling->id) }}"
                                    class="btn btn-primary mt-1 mb-1">Buy Now </a>
                            @else
                                <p class="text-danger mt-2">Currently unavailable</p>
                            @endif
                            <div class="product-content-right tooltip-style">
                                <button class="font-inc">
                                    <i class="@if (isset($best_selling->is_favourite)) fa-solid fa-heart heart @else icon-heart @endif"
                                        id="{{ $best_selling->id }}" onclick="addToWishList(this.id)"></i>
                                    <span>Wishlist</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
</div>
</div>

<div class="contact-area-touch pt-50 pb-50">
    <div class="container">
        <div class="contact-home contact-info-wrap-3">
            <h3>Get in touch</h3>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <img src="{{ asset($shopInformation->contact_image) }}" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="col-lg-12 col-md-12 pb-12">
                        <div class="single-contact-info-3 mb-30">
                            <ul>
                                <li><i class="icon-location-pin "></i></li>
                            </ul>
                            <h4>Location</h4>
                            <p>{{ $shopInformation->address_line_1 }} <br>{{ $shopInformation->address_line_2 }}
                                {{ $shopInformation->pincode }}</p>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 pt-12">
                        <div class="single-contact-info-3 extra-contact-info mb-30">
                            <h4>Contact</h4>
                            <ul>
                                <li><i class="icon-screen-smartphone"></i> {{ $shopInformation->mobile_number }}</li>
                                <li><i class="icon-envelope "></i> <a
                                        href="#">{{ $shopInformation->email }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <div class="single-contact-info-3 mb-30">
                            <ul>
                                <li><i class="icon-clock "></i></li>
                            </ul>
                            <h4>openning hour</h4>
                            <p>Monday - Saturday. 9:00am - 6:00pm </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="contact-area-contact pt-50 pb-15">
    <div class="container-fluid">
        <div class="contact-home contact-info-wrap-3">
            <h3>Our Location</h3>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-xs-12">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15633.019502233423!2d78.0043741!3d11.6051696!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xe2c8e250a360799!2sNithi%20Tex!5e0!3m2!1sen!2sin!4v1667043910833!5m2!1sen!2sin"
                        width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- <div class="row">
                    <div class="col-md-12">
                        @php
                            $popup_image = \App\Models\About::value('popup_image');
                        @endphp
                        <img src="{{ $popup_image }}" alt="popup" class="img-responsive">
                        <input type="hidden" name="hdImgUrl" id="hdImgUrl" value="{{ $popup_image }}">
                    </div>
                </div> --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="popupmodal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeModel" aria-label="Close">
                <span aria-hidden="true"></span>
            </button>
            <div class="modal-body">
                @php
                    $popup_image = \App\Models\About::value('popup_image');
                    $play_store_url = \App\Models\ReferralSettings::value('play_store_url');
                    $app_store_url = \App\Models\ReferralSettings::value('app_store_url');
                @endphp
                <img src="{{ $popup_image }}" alt="popup" class="img-fluid" height="400">
                <input type="hidden" name="hdImgUrl" id="hdImgUrl" value="{{ $popup_image }}">
                <div class="popup-modal-apps">
                    <div>
                        <a href="{{ $play_store_url }}">
                            <img src="{{ asset('frontend/assets/images/img_playstore.png') }}" class="img-fluid"
                                alt="">
                        </a>
                    </div>
                    <div>
                        <a href="{{ $app_store_url }}">
                            <img src="{{ asset('frontend/assets/images/img_appstore.png') }}" class="img-fluid"
                                alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
