@foreach ($shop_all_products as $shop_all_product)
    @php
        $seller_url = '';
        if (Auth::check()) {
            if (Auth::user()->userrole_id == 1) {
                $product_discount = $shop_all_product->product_discount;
            } else {
                $product_discount = $shop_all_product->seller_discount;
                $seller_url = 'seller/';
            }
        } else {
            $product_discount = $shop_all_product->product_discount;
        }
    @endphp
    <div class="shop-list-wrap mb-30">
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-6 col-6">
                <div class="product-list-img">
                    <a
                        href="{{ url(($seller_url == '' ? '' : $seller_url) . 'product/details/' . $shop_all_product->id . '/' . $shop_all_product->product_slug) }}{{ isset($offer) ? '?offer=1' : '' }}">
                        <img src="{{ asset($shop_all_product->product_image) }}" alt="Product Style" class="img-fluid">
                    </a>
                    <div class="product-list-quickview">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"
                            id="{{ $shop_all_product->id }}" onclick="productView(this.id)">
                            <i class="icon-size-fullscreen icons"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-7 col-md-6 col-sm-6 col-6">
                <div class="shop-list-content product-content-wrap-2">
                    <h3><a
                            href="{{ url(($seller_url == '' ? '' : $seller_url) . 'product/details/' . $shop_all_product->id . '/' . $shop_all_product->product_slug) }}{{ isset($offer) ? '?offer=1' : '' }}">{{ $shop_all_product->product_name }}</a>
                    </h3>
                    @if ($product_discount == 0.0)
                        <div class="product-price">
                            <span class="new-price">₹{{ round($shop_all_product->product_price) }}</span>
                        </div>
                    @else
                        <div class="product-price">
                            <span class="new-price">₹{{ round($product_discount) }}</span>
                            <span class="old-price">₹{{ round($shop_all_product->product_price) }}</span>
                        </div>
                    @endif

                    <p>{{ $shop_all_product->short_description }}</p>
                    <div class="product-list-action">
                        <input type="hidden" id="product_id" value="{{ $shop_all_product->id }}">
                        <span id="pname" hidden>{{ $shop_all_product->product_name }}</span>
                        <input type="hidden" id="qty" value="1">
                        @if ($shop_all_product->current_stock > 0)
                            <button title="Add To Cart" type="submit" id="{{ $shop_all_product->id }}"
                                onclick="addToCartsimple(this.id, null)"><i class="icon-basket-loaded"></i></button>
                        @endif
                        <button title="Wishlist"><i
                                class="@if (isset($shop_all_product->is_favourite)) fa-solid fa-heart heart @else icon-heart @endif"
                                id="{{ $shop_all_product->id }}" onclick="addToWishList(this.id)"></i></button>
                        <button type="button" data-bs-toggle="modal">
                            @if ($product_discount == null)
                                <span class="discount">0%</span>
                            @else
                                @php
                                    $amount = $shop_all_product->product_price - $product_discount;
                                    $discount = ($amount / $shop_all_product->product_price) * 100;
                                @endphp
                                <span class="discount">{{ round($discount) }}%</span>
                            @endif
                        </button>
                        <div>
                            @if ($shop_all_product->current_stock > 0)
                                <a href="{{ url(($seller_url == '' ? '' : $seller_url) . 'product/buynow/' . $shop_all_product->id) }}{{ isset($offer) ? '?offer=1' : '' }}"
                                    style="border-radius: 50px;" class="btn btn-primary buy mt-2">Buy Now </a>
                            @else
                                <p class="text-danger">Currently unavailable</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
