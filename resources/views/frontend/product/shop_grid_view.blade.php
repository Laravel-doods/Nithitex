@foreach($shop_all_products as $shop_all_product)
    @php
    $seller_url = "";
    if(Auth::check()) {
        if (Auth::user()->userrole_id == 1) {
            $product_discount = $shop_all_product->product_discount;    
        }
        else {
            $product_discount = $shop_all_product->seller_discount;
            $seller_url = "seller/";
        }
    }
    else {
        $product_discount = $shop_all_product->product_discount;
    }
    @endphp

    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-6">
        <div class="single-product-wrap mb-35">
            <div class="product-img product-img-zoom mb-15">
                <a href="{{url(($seller_url == "" ? "" : $seller_url).'product/details/'.$shop_all_product->id.'/'.$shop_all_product->product_slug ) }}{{ isset($offer) ? '?offer=1' : '' }}">
                    <img src="{{ asset($shop_all_product->product_image)}}" alt="" class="img-fluid">
                </a>
                @if($product_discount != NULL)
                    @php
                    $amount = $shop_all_product->product_price - $product_discount;
                    $discount = ($amount/$shop_all_product->product_price) * 100;
                    @endphp
                    <span class="pro-badge right bg-red">  {{round($discount)}}%</span>          
                @endif
                <div class="product-action-2 tooltip-style-2">
                    <button title="Wishlist">

                        <i class="@if (isset($shop_all_product->is_favourite)) fa-solid fa-heart heart @else icon-heart @endif" id="{{ $shop_all_product->id }}" onclick="addToWishList(this.id)"></i>
                    </button>
                    
                        <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" id="{{ $shop_all_product->id }}" onclick="productView(this.id)">
                        <i class="icon-size-fullscreen icons"></i></button>
                        <button type="button" data-bs-toggle="modal" >
                            @if($product_discount == NULL)
                                <span>0%</span>            
                                @else
                                @php
                                $amount = $shop_all_product->product_price - $product_discount;
                                $discount = ($amount/$shop_all_product->product_price) * 100;
                                @endphp
                                <span>{{round($discount)}}%</span>
                    
                            @endif
                        </button>
                            
                </div>
            </div>
            <div class="product-content-wrap-2 text-center">
            
                <h3><a href="{{url(($seller_url == "" ? "" : $seller_url).'product/details/'.$shop_all_product->id.'/'.$shop_all_product->product_slug ) }}{{ isset($offer) ? '?offer=1' : '' }}">{{ $shop_all_product->product_name }}</a></h3>
                @if ($product_discount == 0.00)
                    <div class="product-price">
                    <span class="new-price">₹{{ round($shop_all_product->product_price) }}</span>
                    </div>
                    @else
                    <div class="product-price">
                    <span class="new-price">₹{{ round($product_discount) }}</span>
                    <span class="old-price">₹{{ round($shop_all_product->product_price) }}</span>
                    </div>
                @endif
            </div>
            <div class="product-content-wrap-2 product-content-position text-center">
            
                <h3><a href="{{url(($seller_url == "" ? "" : $seller_url).'product/details/'.$shop_all_product->id.'/'.$shop_all_product->product_slug ) }}{{ isset($offer) ? '?offer=1' : '' }}">{{ $shop_all_product->product_name }}</a></h3>
                @if ($product_discount == 0.00)
                    <div class="product-price">
                    <span class="new-price">₹{{ round($shop_all_product->product_price) }}</span>
                    </div>
                    @else
                    <div class="product-price">
                    <span class="new-price">₹{{ round($product_discount) }}</span>
                    <span class="old-price">₹{{ round($shop_all_product->product_price) }}</span>
                </div>
                @endif
                <div class="pro-add-to-cart">
                    <input type="hidden" id="product_id" value="{{ $shop_all_product->id }}">
                    <span id="pname" hidden>{{ $shop_all_product->product_name }}</span>
                    <input type="hidden" id="qty" value="1">
                    @if ($shop_all_product->current_stock > 0)
                        <div class="product-action-buttons">
                            <button title="Add to Cart"  type="submit" id="{{ $shop_all_product->id }}" onclick="addToCartsimple(this.id , null)" >Add To Cart</button>
                            <a href="{{url(($seller_url == "" ? "" : $seller_url).'product/buynow/'.$shop_all_product->id) }}{{ isset($offer) ? '?offer=1' : '' }}" style="border-radius: 50px;" class="btn btn-primary buy">Buy Now </a>
                        </div>
                    @else
                        <p class="text-danger">Currently unavailable</p>
                    @endif
                    
                    
                </div>
            </div>
        </div>
    </div>
@endforeach
