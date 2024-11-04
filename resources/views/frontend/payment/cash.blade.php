@extends('frontend.main_master')
@section('content')
@section('title')
    Checkout | Cash on Delivery | India's No 1 Online Saree Shop - Nithitex
@endsection

<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">Cash on Delivery</li>
            </ul>
        </div>
    </div>
</div>
<div class="about-us-area pt-120 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="your-order-area">

                    <h3>Summary</h3>
                    <div class="your-order-wrap gray-bg-4">
                        <form action="{{ route('cash.order') }}" method="post" id="payment-form">
                            @csrf
                            <input type="hidden" name="cart_true" id="cart_true" value="{{ $cart_true }}">
                            <input type="hidden" name="shipping_charge" id="shipping_charge"
                                value="{{ $shipping_charge }}">
                            <input type="hidden" name="name" value="{{ $data['shipping_name'] }}">
                            <input type="hidden" name="email" value="{{ $data['shipping_email'] }}">
                            <input type="hidden" name="phone" value="{{ $data['shipping_phone'] }}">
                            <input type="hidden" name="alternative_number" value="{{ $data['alternative_number'] }}">
                            <input type="hidden" name="door_no" value="{{ $data['door_no'] }}">
                            <input type="hidden" name="street_address" value="{{ $data['street_address'] }}">
                            <input type="hidden" name="city_name" value="{{ $data['city_name'] }}">
                            <input type="hidden" name="state_name" value="{{ $data['state_name'] }}">
                            <input type="hidden" name="pin_code" value="{{ $data['pin_code'] }}">
                            <input type="hidden" name="hddiscount" value="{{ $data['coupon_discount'] }}">
                            <input type="hidden" name="hdcoupon_id" value="{{ $data['coupon_id'] }}">
                            <div class="your-order-info-wrap">
                                <div class="your-order-info">
                                    <ul>
                                        <li>Product <span>Total</span></li>
                                    </ul>
                                </div>
                                @if ($cart_true == 1)
                                    @foreach ($carts as $item)
                                        <div class="your-order-middle">
                                            <ul>

                                                <li>{{ $item->name }} X {{ $item->qty }}
                                                    <span>₹{{ round($item->price) }} </span>
                                                </li>
                                            </ul>
                                        </div>
                                    @endforeach

                                    <div class="your-order-info order-subtotal">
                                        <ul>
                                            <li>Subtotal <span>₹{{ round($cartTotal) }} </span></li>
                                        </ul>
                                        <input type="hidden" name="cart_subtotal" id="cart_subtotal"
                                            value="{{ $cartTotal }}">
                                    </div>

                                    @if ($coupon_discount)
                                        <div class="your-order-info order-shipping">
                                            <ul>
                                                <li>Discount ( {{ $coupon_discount }}% )<p>
                                                        <span>₹{{ round(($coupon_discount * $cartTotal) / 100) }}</span>
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="your-order-info order-shipping">
                                        <ul>

                                            <li>Shipping (* {{ $totQty }})<p>
                                                    <span>₹{{ round($shipping_charge) }}</span>
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="your-order-info order-total">
                                        <ul>
                                            <li>Total
                                                <span>₹{{ round($cart_total - ($coupon_discount * $cartTotal) / 100) }}
                                                </span>
                                            </li>
                                        </ul>
                                        <input type="hidden" name="cart_total" id="cart_total"
                                            value="{{ round($cart_total - ($coupon_discount * $cartTotal) / 100) }}">

                                    </div>
                                @else
                                    <div class="your-order-middle">
                                        <ul>
                                            <li>{{ $buy_product_name }} X {{ $buy_product_qty }}
                                                <span>₹{{ round($buy_price) }} </span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="your-order-info order-subtotal">
                                        <ul>
                                            <li>Subtotal <span>₹{{ round($buy_price) }} </span></li>
                                        </ul>
                                    </div>

                                    @if ($coupon_discount)
                                        <div class="your-order-info order-shipping">
                                            <ul>
                                                <li>Discount ( {{ $coupon_discount }}% ) <p>
                                                        <span>₹{{ round(($coupon_discount * $buy_price) / 100) }}</span>
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="your-order-info order-shipping">
                                        <ul>
                                            <li>Shipping (* {{ $buy_product_qty }})<p>
                                                    <span>₹{{ round($shipping_charge) }}</span>
                                                </p>
                                            </li>
                                        </ul>
                                    </div>

                                    <input type="hidden" name="buy_now_price" id="buy_now_price"
                                        value="{{ $buy_price }}">
                                    <input type="hidden" name="buy_now_product_name" id="buy_now_product_name"
                                        value="{{ $buy_product_name }}">
                                    <input type="hidden" name="buy_now_product_qty" id="buy_now_product_qty"
                                        value="{{ $buy_product_qty }}">
                                    <input type="hidden" name="buy_now_product_id" id="buy_now_product_id"
                                        value="{{ $buy_product_id }}">
                                    <input type="hidden" name="buy_now_variant_id" id="buy_now_variant_id"
                                        value="{{ $buy_variant_id }}">
                                    <input type="hidden" name="buy_now_total" id="buy_now_total"
                                        value="{{ round($buy_total - ($coupon_discount * $buy_price) / 100) }}">

                                    <div class="your-order-info order-total">
                                        <ul>
                                            <li>Total <span id="total"
                                                    name="total">₹{{ round($buy_total - ($coupon_discount * $buy_price) / 100) }}
                                                </span></li>
                                        </ul>
                                    </div>
                                @endif

                            </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8">
                <div class="col d-flex justify-content-center align-items-center">
                    <img src="{{ asset('frontend/assets/images/logo/cod.png') }}" class="img-fluid" alt="">
                </div>

                <div class="d-flex justify-content-center align-items-center mt-20 float-right">
                    <button type="submit" class="btn btn-flat btn-dark submitBtn" title="Confirm order">Confirm
                        Order</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
