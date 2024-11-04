@php
    $url = 'frontend.main_master';
    $seller_url = '';
    if (Auth::check()) {
        if (Auth::user()->userrole_id == 1) {
            $product_discount = $product_details->product_discount;
        } else {
            $product_discount = $product_details->seller_discount;
            $url = 'seller.seller_main_master';
            $seller_url = 'seller/';
        }
    } else {
        $product_discount = $product_details->product_discount;
    }
@endphp
@extends($url)
@section('content')
@section('title')
    Product Details | India's No 1 Online Saree Shop - Nithitex
@endsection


<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">Product Details</li>
            </ul>
        </div>
    </div>
</div>
<div class="product-details-area pt-50 pb-50">
    <div class="container">

        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="product-details-tab">
                    <div class="pro-dec-big-img-slider">
                        @foreach ($multiImage as $mulimg)
                            <div class="easyzoom-style">
                                <div class="easyzoom easyzoom--overlay">
                                    <a href="{{ asset($mulimg->product_mult_image) }}">
                                        <img src="{{ asset($mulimg->product_mult_image) }}" alt=""
                                            class="img-fluid">
                                    </a>
                                </div>
                                <a class="easyzoom-pop-up img-popup" href="{{ asset($mulimg->product_mult_image) }}"><i
                                        class="icon-size-fullscreen"></i></a>
                            </div>
                        @endforeach
                    </div>

                    <div class="product-dec-slider-small product-dec-small-style1">
                        @if ($multiImage->count() > 1)
                            @foreach ($multiImage as $mulimg)
                                <div class="product-dec-small">
                                    <img src="{{ asset($mulimg->product_mult_image) }}" alt="">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="product-details-content pro-details-content-mrg">
                    <input type="hidden" id="product_id" value="{{ $product_details->id }}">
                    <h2><span id="pname">{{ $product_details->product_name }}</span></h2>
                    <p>{!! $product_details->short_description !!}</p>
                    <div class="pro-details-price">
                        <span class="new-price" id="product-selling-price">₹{{ round($product_discount) }}</span>
                        <span class="old-price"
                            id="product-original-price">₹{{ round($product_details->product_price) }}</span>
                    </div>
                    @if ($seller_url != '')
                        <div>
                            <strong>Customer Price</strong>
                            <span><s>₹{{ round($product_details->product_discount) }}</s></span>
                        </div>
                    @endif
                    <div class="offer">
                        @if ($product_discount == null)
                            <span style="color:red;"> NO DISCOUNT </span>
                        @else
                            @php
                                $amount = $product_details->product_price - $product_discount;
                                $discount = ($amount / $product_details->product_price) * 100;
                            @endphp
                            <strong>Discount Percentage : </strong><span
                                style="color:red; font-weight:bolder;font-size:15px;"
                                id="discountPercentage">{{ round($discount) }}%</span>
                        @endif
                    </div>
                    @if ($product_details->color)
                        <div class="pro-details-color-wrap"><br>
                            <span>Color:</span>
                            <div class="pro-details-color-content">
                                <ul>
                                    @foreach ($productGroup as $product)
                                        <li>
                                            <a class="@if ($product_details->color_id == $product->color_id) product-Item-select @endif"
                                                style="background-color: {{ $product->color->color_code }};"
                                                @if ($product_details->id != $product->id) href="{{ url('product/details/' . $product->id . '/' . $product->product_slug) }}{{ $offer == 1 ? '?offer=1' : '' }}" @endif>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    @if ($productVariants->isNotEmpty())
                        <div class="pro-details-size-wrap"><br>
                            <span>Size:</span>
                            <div class="pro-details-size-content">
                                <ul>
                                    @foreach ($productVariants as $key => $variant)
                                        @if ($key == 0)
                                            <input type="hidden" id="firstvariantID" value="{{ $variant->id }}">
                                        @endif
                                        <li onclick="productVariantData({{ $variant->id }})" class="sizeListItem"
                                            data-variant-id="{{ $variant->id }}">
                                            <a>
                                                {{ $variant->size }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    @if ($product_details->current_stock > 0)
                        <p class="text-danger" id="outofstock" style="display: none">Currently unavailable</p>
                        <div class="pro-details-quality stockProduct" >
                            <span>Quantity:</span>
                            <div class="cart-plus-minus">
                                <input class="cart-plus-minus-box" readonly type="text" id="qty" min="1"
                                    name="qtybutton" value="1">
                            </div>
                        </div><br>
                        
                    @else
                        <p class="text-danger mb-3 mt-2">Currently unavailable</p>
                    @endif

                    <div class="pro-details-quality">
                        <input type="hidden" id="hidstk" value="{{ $product_details->current_stock }}" />
                        <span id="currentStocks">Available Quantity: {{ $product_details->current_stock }}</span>
                    </div>
                    <div class="pro-details-quality">
                        <span id="pro-sku">Product SKU: {{ $product_details->product_sku }}</span>
                    </div>
                    <div class="product-details-meta">
                        <ul>
                            <li><span>Categories:</span> {{ $product_details->category->category_name }}</li>
                            @if ($product_details->tags)
                                <li><span>Tag: </span> {{ $product_details->tags }}</li>
                            @endif
                        </ul>
                    </div>
                    <div class="pro-details-action-wrap">
                        <input type="hidden" id="hdTodayOffer1" name="hdTodayOffer1"
                            value="{{ $offer }}">
                        @if ($product_details->current_stock > 0)
                            <div class="pro-details-add-to-cart stockProduct">
                                <button type="submit" class="btn btn-flat btn-dark" data-offer="{{ $offer }}"
                                    data-variant-id="" title="Add to Cart" onclick="addToCart(this)">Add To
                                    Cart</button>
                            </div>
                            <div class="stockProduct">
                                <form
                                    action="{{ url(($seller_url == '' ? '' : $seller_url) . 'productdetails/buynow/' . $product_details->id) }}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" id="hdbuyqty" name="hdbuyqty" value="1">
                                    <input type="hidden" id="hdvariantID" name="hdvariantID" value="0">
                                    <input type="hidden" id="hdTodayOffer" name="hdTodayOffer"
                                        value="{{ $offer }}">
                                    <button type="submit" class="btn btn-primary" title="Buy Now">Buy Now</button>
                                </form>
                            </div>
                        @endif

                        <div class="pro-details-action">
                            <a title="Add to Wishlist"><i
                                    class="@if (isset($product_details->is_favourite)) fa-solid fa-heart heart @else icon-heart @endif  wishlist-icon"
                                    data-variant-id="" id="{{ $product_details->id }}"
                                    onclick="productdetailaddToWishList(this.id)"></i></a>
                            <a class="whatsapp" title="whatsapp" href="{{ $share }}" target="_blank"><i
                                    class="icon-share"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="description-review-wrapper pb-110">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="dec-review-topbar nav mb-45">
                    <a class="active" data-bs-toggle="tab" href="#des-details1">Description</a>
                    <a data-bs-toggle="tab" href="#des-details2">Product Video</a>
                </div>
                <div class="tab-content dec-review-bottom">
                    <div id="des-details1" class="tab-pane active">
                        <div class="description-wrap">
                            {!! $product_details->long_description !!}
                        </div>
                    </div>
                    <div id="des-details2" class="tab-pane">
                        <div class="specification-wrap table-responsive">
                            <iframe width="100%" height="500" src="{{ $product_details->product_video_url }}"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="related-product pb-115">
    <div class="container">
        <div class="section-title mb-45 text-center">
            <h2>Related Products</h2>
        </div>
        <div class="product-slider-active-3 nav-style-3">
            @foreach ($related_products as $related_product)
                <div class="product-plr-1">
                    <div class="single-product-wrap">
                        <div class="product-img product-img-zoom mb-15">
                            <a
                                href="{{ url(($seller_url == '' ? '' : $seller_url) . 'product/details/' . $related_product->id . '/' . $related_product->product_slug) }}{{ $offer == 1 ? '?offer=1' : '' }}">
                                <img src="{{ asset($related_product->product_image) }}" alt=""
                                    class="img-fluid">
                            </a>
                            <div class="product-action-2 tooltip-style-2">
                                <button title="Wishlist"><i
                                        class="@if (isset($related_product->is_favourite)) fa-solid fa-heart heart @else icon-heart @endif"
                                        id="{{ $related_product->id }}"
                                        onclick="addToWishList(this.id)"></i></button>
                                <button title="Quick View" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                    id="{{ $related_product->id }}" onclick="productView(this.id)"><i
                                        class="icon-size-fullscreen icons" id="{{ $related_product->id }}"
                                        onclick="productView(this.id)"></i></button>

                            </div>
                        </div>
                        <div class="product-content-wrap-2 text-center">
                            <h3><a
                                    href="{{ url(($seller_url == '' ? '' : $seller_url) . 'product/details/' . $related_product->id . '/' . $related_product->product_slug) }}">{{ $related_product->product_name }}</a>
                            </h3>
                            @if ($related_product->product_discount == 0.0)
                                <div class="product-price">
                                    <span class="new-price">₹{{ round($related_product->product_price) }}</span>
                                </div>
                            @else
                                <div class="product-price">
                                    <span class="new-price">₹{{ round($related_product->product_discount) }}</span>
                                    <span class="old-price">₹{{ round($related_product->product_price) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="product-content-wrap-2 product-content-position text-center">
                            <h3><a
                                    href="{{ url(($seller_url == '' ? '' : $seller_url) . 'product/details/' . $related_product->id . '/' . $related_product->product_slug) }}">{{ $related_product->product_name }}</a>
                            </h3>
                            @if ($related_product->product_discount == 0.0)
                                <div class="product-price">
                                    <span class="new-price">₹{{ round($related_product->product_price) }}</span>
                                </div>
                            @else
                                <div class="product-price">
                                    <span class="new-price">₹{{ round($related_product->product_discount) }}</span>
                                    <span class="old-price">₹{{ round($related_product->product_price) }}</span>
                                </div>
                            @endif
                            <div class="pro-add-to-cart">
                                <input type="hidden" id="product_id" value="{{ $related_product->id }}">
                                <span id="pname" hidden>{{ $related_product->product_name }}</span>
                                <input type="hidden" id="qty" value="1">
                                <input type="hidden" value="{{ $offer ?? '' }}" id="offer">
                                <button title="Add to Cart" type="submit" id="{{ $related_product->id }}"
                                    onclick="addToCartsimple(this.id, null)">Add To Cart</button>
                                <a href="{{ url(($seller_url == '' ? '' : $seller_url) . 'product/buynow/' . $related_product->id) }}{{ isset($offer) ? '?offer=1' : '' }}"
                                    style="border-radius: 50px; margin-top:8px;" class="btn btn-primary buy">Buy Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    var isseller = {{ $seller_url == '' ? 0 : 1 }};
    var productId = {{ $product_details->id }};
    var is_variant = {{ $product_details->is_product_variant }};
</script>
<script src="{{ asset('frontend/assets/js/product.js?v=2') }}"></script>
@endsection
