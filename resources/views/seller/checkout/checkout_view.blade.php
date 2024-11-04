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
                <li class="active">Checkout </li>
            </ul>
        </div>
    </div>
</div>
<div class="checkout-main-area pt-50 pb-50">
    <div class="container">
        <div class="checkout-wrap pt-30">

            <form class="register-form" action="{{ route('seller.checkout.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-7">
                        <div class="billing-info-wrap mr-50">
                            <h3>Shipping Details</h3>
                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="billing-info mb-20">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" name="shipping_name" id="shipping_name"
                                            value="{{ old('name') }}" required="" placeholder="Enter Your Name"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="billing-info mb-20" style="display: none">
                                        <label>Email Address <span class="text-danger">*</span></label>
                                        <input type="text" name="shipping_email" id="shipping_email"
                                            value="{{ Auth::user()->email }}" placeholder="Enter Your E-mail" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="billing-info mb-20">
                                        <label>Phone <span class="text-danger">*</span></label>

                                        <input type="text" value="{{ old('phone') }}" name="shipping_phone"
                                            id="shipping_phone" required="" placeholder="Enter Mobile Number"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="billing-info mb-20">
                                        <label>Alternative Phone Number</label>

                                        <input type="text" name="alternative_number"
                                            value="{{ old('alternative_number') }}" id="alternative_number"
                                            placeholder="Enter Alternative Mobile Number" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="billing-info mb-20">
                                        <label>Door No. / Flat No. / Floor No.<span class="text-danger">*</span></label>
                                        <input class="billing-address" value="{{ old('door_no') }}" name="door_no"
                                            id="door_no" placeholder="Enter Door No. / Flat No. / Floor No."
                                            type="text" required="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="billing-info mb-20">
                                        <label>Street Address <span class="text-danger">*</span></label>
                                        <input class="billing-address" name="street_address" id="street_address"
                                            placeholder="Enter Street Address" value="{{ old('street_address') }}"
                                            type="text" required="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="billing-info mb-20">
                                        <label>City<span class="text-danger">*</span></label>
                                        <input class="billing-address" name="city_name" id="city_name"
                                            placeholder="Enter City Name" type="text" value="{{ old('city_name') }}"
                                            required="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="billing-info mb-20">
                                        <label>State <span class="text-danger">*</span></label>
                                        <select id="state_name" name="state_name" class="billing-address"
                                            title="Please Select State" required onchange="bindShippingCost();">
                                            <option value="">Select State</option>
                                            @foreach ($state as $item)
                                                <option value="{{ $item->state_name }}"
                                                    shipping="{{ $item->shipping_charge }}"
                                                    free-deivery="{{ $item->is_free_delivery }}"
                                                    cod="{{ $item->cod_charge }}">
                                                    {{ $item->state_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('state_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="billing-info mb-20">
                                        <label>Pin Code <span class="text-danger">*</span></label>
                                        <input type="text" name="pin_code" id="pin_code"
                                            value="{{ old('pin_code') }}" required="" placeholder="Enter Pincode"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="cart_true" id="cart_true" value="{{ $cart_true }}">
                    <div class="col-lg-5">
                        <div class="your-order-area">
                            <h3>Your order</h3>
                            <div class="your-order-wrap gray-bg-4">
                                <div class="your-order-info-wrap">
                                    <div class="your-order-info">
                                        <ul>
                                            <li>Product <span>Total</span></li>
                                        </ul>
                                    </div>
                                    @if ($cart_true == 1)
                                        @php
                                            $totQty = 0;
                                        @endphp

                                        @foreach ($carts as $item)
                                            <div class="your-order-middle">
                                                <ul>
                                                    <li>{{ $item->name }} X {{ $item->qty }}
                                                        <span>₹{{ round($item->price) }} </span>
                                                        @if ($item->variant_id != null)
                                                            <br>
                                                            size: {{ $item->productVariant->size }}
                                                        @endif
                                                        @if ($item->product->color_id != null)
                                                            <br>
                                                            color: {{ $item->product->color->color_name }}
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>

                                            @php
                                                $totQty += $item->qty;
                                            @endphp
                                        @endforeach

                                        <div class="your-order-info order-subtotal">
                                            <ul>
                                                <li>Subtotal <span>₹{{ round($cartTotal) }} </span>
                                                    <input type="hidden" name="hdCartTotal" id="hdCartTotal"
                                                        value="{{ $cartTotal }}">
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="your-order-info order-discount hidden">
                                            <ul>
                                                <li>Discount (<div class="badge text-dark " id="hddiscountshow"
                                                        value="0"></div>)<span id="tot_coupon_amount"></span>
                                                    <input type="hidden" name="hdtotcouponamount"
                                                        id="hdtotcouponamount" value="0">
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="your-order-info order-shipping">
                                            <ul>
                                                <li>Shipping ( * {{ $totQty }} ) <span
                                                        id="tot_shipping_cart">₹0</span>
                                                    <input type="hidden" name="hdtotalqty" id="hdtotalqty"
                                                        value="{{ $totQty }}">
                                                    <input type="hidden" name="hdtotshippingcharge"
                                                        id="hdtotshippingcharge" value="0">
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="your-order-info order-total">
                                            <ul>
                                                <input id="hdtotal_Cart" name="hdtotal_Cart" type="hidden"
                                                    value="{{ round($cartTotal) }}">
                                                <li>Total <span id="total"
                                                        name="total">₹{{ round($cartTotal) }} </span></li>
                                            </ul>
                                        </div>
                                    @else
                                        <input type="hidden" name="catWeight" id="catWeight"
                                            value="{{ $product->category->weight }}">
                                        <div class="your-order-middle">
                                            <ul>
                                                <li>{{ $product->product_name }} X {{ $quantity }}
                                                    <span>₹{{ round($buynow_price) }} </span>
                                                    @if ($variant_size != null)
                                                        <br>
                                                        size: {{ $variant_size }}
                                                    @endif
                                                    @if ($product->color_id != null)
                                                        <br>
                                                        color: {{ $product->color->color_name }}
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="your-order-info order-subtotal">
                                            <ul>
                                                <li>Subtotal <span>₹{{ round($buynow_price) }} </span></li>
                                                <input type="hidden" name="hdCartTotal" id="hdCartTotal"
                                                    value="{{ $buynow_price }}">
                                            </ul>
                                        </div>
                                        <div class="your-order-info order-discount hidden">
                                            <ul>
                                                <li>Discount (<div class="badge text-dark" id="hddiscountshow"
                                                        value="0"></div>)<span id="tot_coupon_amount"></span>
                                                    <input type="hidden" name="hdtotcouponamount"
                                                        id="hdtotcouponamount" value="0">
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="your-order-info order-shipping">
                                            <ul>
                                                <li>Shipping ( * {{ $quantity }} ) <span
                                                        id="tot_shipping_buynow"></span>
                                                    <input type="hidden" name="hdtotalqty" id="hdtotalqty"
                                                        value="{{ $quantity }}">
                                                    <input type="hidden" name="hdtotshippingcharge"
                                                        id="hdtotshippingcharge" value="0">
                                                </li>
                                            </ul>
                                        </div>

                                        <input type="hidden" name="buy_now_price" id="buy_now_price"
                                            value="{{ $buynow_price }}">
                                        <input type="hidden" name="buy_now_product_id" id="buy_now_product_id"
                                            value="{{ $product_id }}">
                                        <input type="hidden" name="buy_now_variant_id" id="buy_now_variant_id"
                                            value="{{ $variant_id }}">
                                        <input type="hidden" name="buy_now_product_name" id="buy_now_product_name"
                                            value="{{ $product->product_name }}">
                                        <input type="hidden" name="buy_now_product_qty" id="buy_now_product_qty"
                                            value="{{ $quantity }}">
                                        <div class="your-order-info order-total">
                                            <ul>
                                                <li>
                                                    <input id="hdtotal_amount" name="hdtotal_amount" type="hidden"
                                                        value="{{ round($buynow_price) }}">
                                                    Total <span id="total"
                                                        name="total">₹{{ round($buynow_price) }} </span>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                                @if ($offer == 0)
                                    <div class="mt-3">
                                        <p>If you have coupon code, please enter it here!</p>
                                        <div class="input-group">
                                            <input type="hidden" name="hddiscount" id="hddiscount" value="0">
                                            <input type="hidden" name="hdcoupon_id" id="hdcoupon_id"
                                                value="0">
                                            <input type="text" class="form-control" id="coupon_code"
                                                name="coupon_code" placeholder="Enter coupon code" />
                                            <span class="mt-1"><button type="button" title="Apply Coupon"
                                                    class="btn btn-flat btn-dark" onclick="couponcalculation();"
                                                    id="btnCoupon">Apply
                                                    Coupon</button></span>
                                        </div>
                                        <div id="coupon_status"></div>
                                    </div>
                                @else
                                    <input type="hidden" name="hddiscount" id="hddiscount" value="0">
                                    <input type="hidden" name="hdcoupon_id" id="hdcoupon_id" value="0">
                                    <input type="hidden" class="form-control" id="coupon_code" name="coupon_code"
                                        placeholder="Enter coupon code" />
                                @endif

                                <div class="payment-method">
                                    <div class="pay-top sin-payment sin-payment-3">
                                        <input id="payment-method-4" class="input-radio" type="radio"
                                            value="razorpay" name="payment_method">
                                        <label for="payment-method-4">Online Payment</label>
                                        <div class="payment-box payment_method_bacs online hidden">
                                            <input type="hidden" name="hdShipping_online" id="hdShipping_online"
                                                value="0">
                                            <p class="content">FOR ONLINE PAYMENT THE SHIPPING COST IS </p><span
                                                id="shipping_online" class="text-danger"
                                                style="font-weight:bolder">₹0</span>
                                        </div>
                                    </div>
                                    <div class="pay-top sin-payment">
                                        <input id="payment-method-3" class="input-radio" type="radio"
                                            value="cash" name="payment_method">
                                        <label for="payment-method-3">Cash on delivery </label>
                                        <div class="payment-box payment_method_bacs cod hidden">
                                            <input type="hidden" name="hdShipping_cod" id="hdShipping_cod"
                                                value="0">
                                            <p class="content">We are giving correct market price but if the payment is
                                                in Cash on
                                                Delivery means cost will be high if you feel is high Cash on Delivery
                                                means please reduce the number of COD options. Kindly choose a reliable
                                                online payment reduced very low cost of shipping platform.</p>
                                            <span id="shipping_cod" class="text-danger"
                                                style="font-weight:bolder">₹0</span>
                                        </div>
                                    </div>

                                    <div class="payment-box payment_method_bacs cod hidden">
                                        <input id="hdOrd_total" name="hdOrd_total" type="hidden"
                                            value="@if ($cart_true == 0) {{ $product->seller_discount }}@else{{ $cartTotal }} @endif" />

                                        <p style="color:darkblue; font-weight:bolder;"> Order Total ( ₹ <span
                                                id="spnOrd_total"></span> + Margin Amount )</p>

                                        <div class="fw-bold">Cash to be Collected</div>
                                        <input id="hdcarttotal" type="hidden"
                                            value="@if ($cart_true == 0) {{ $product->seller_discount }}@else{{ $cartTotal }} @endif">
                                        <input id="hdmargin_amount" name="hdmargin_amount" type="hidden"
                                            value="0">
                                        <input id="collectcash_amount" type="text" class="form-control"
                                            name="collectcash_amount" onkeyup="calcmargin();">
                                        <div id="spnMargin"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="Place-order">
                                <button type="submit" class="btn btn-flat btn-dark" title="Add to Cart" disabled
                                    id="btnPlaceOrder">Place Order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
<script>
    function calcmargin() {
        $("#btnPlaceOrder").prop("disabled", true);
        if ($("#collectcash_amount").val() != "") {
            var cartTotal = "0";

            if ($("#cart_true").val() != "1") {
                cartTotal = $("#hdtotal_amount").val();
            } else {
                cartTotal = $("#hdtotal_Cart").val();
            }

            var collectcash_amount = $("#collectcash_amount").val();

            var totMargin = parseFloat(collectcash_amount) - parseFloat(cartTotal);
            $("#hdmargin_amount").val(parseFloat(totMargin));
            if (Math.sign(totMargin) < 0) {
                $("#spnMargin").html("Your Margin : ₹" + Math.abs(parseFloat(totMargin)));
                $("#spnMargin").removeClass("text-success");
                $("#spnMargin").addClass("text-danger");
                $("#btnPlaceOrder").prop("disabled", true);
            } else {
                $("#spnMargin").html("Your Margin : ₹" + parseFloat(totMargin));
                $("#spnMargin").removeClass("text-danger");
                $("#spnMargin").addClass("text-success");
                $("#btnPlaceOrder").prop("disabled", false);
            }
        } else {
            $("#spnMargin").html("");
        }
    }

    var carts = @json($carts);

    function bindShippingCost() {
        // Validate that a state is selected
        var selectedState = $("#state_name option:selected").val();
        if (!selectedState) {
            alert("Please select a state before choosing a payment method.");
            $('input[name="payment_method"]').prop("checked", false);
            return;
        }

        var shipping_online = $("#state_name option:selected").attr("shipping");
        var shipping_cod = $("#state_name option:selected").attr("cod");
        var freeDeivery = $("#state_name option:selected").attr("free-deivery");

        var totQty = $("#hdtotalqty").val();
        var cartTotal = $("#hdCartTotal").val();
        var cart_true = $("#cart_true").val();
        var catWeight = $("#catWeight").val();
        var discountage_percentage = parseInt($("#hddiscount").val());

        var tot_shipping_charge = 0;
        var tot_cod_charge = 0;
        var netTotal = 0;

        var discount_amt = Math.round(cartTotal * discountage_percentage / 100);

        $("#tot_coupon_amount").html('₹' + Math.round(discount_amt));
        $("#hdtotcouponamount").val(discount_amt);
        $("#hdtotcouponamount").html('₹' + Math.round(discount_amt));

        $("#hddiscountshow").val(discountage_percentage);
        $("#hddiscountshow").html(Math.round(discountage_percentage) + '%');

        if ($('input[name="payment_method"]:checked').val() == "razorpay") {
            if (cart_true == 1) {
                carts.forEach(function(item) {
                    tot_shipping_charge += parseFloat(item.weight) * item.qty * shipping_online;
                });
            } else {
                tot_shipping_charge = parseInt(totQty * catWeight * shipping_online);
            }
            $("#hdtotshippingcharge").val(tot_shipping_charge);
            if (freeDeivery == 1) {
                netTotal = parseInt(cartTotal) - parseInt(discount_amt);
            } else {
                netTotal = parseInt(cartTotal) + parseInt(tot_shipping_charge) - parseInt(discount_amt);
                $("#tot_shipping_cart").html('₹' + tot_shipping_charge);
                $("#tot_shipping_buynow").html('₹' + tot_shipping_charge);
            }
            $("#btnPlaceOrder").prop("disabled", false);
        } else if ($('input[name="payment_method"]:checked').val() == "cash") {
            if (cart_true == 1) {
                carts.forEach(function(item) {
                    tot_cod_charge += parseFloat(item.weight) * item.qty * shipping_cod;
                });
            } else {
                tot_cod_charge = parseInt(totQty * catWeight * shipping_cod);
            }
            $("#hdtotshippingcharge").val(tot_cod_charge);
            if (freeDeivery == 1) {
                netTotal = parseInt(cartTotal) - parseInt(discount_amt);
            } else {
                netTotal = parseInt(cartTotal) + parseInt(tot_cod_charge) - parseInt(discount_amt);
                $("#tot_shipping_cart").html('₹' + tot_cod_charge);
                $("#tot_shipping_buynow").html('₹' + tot_cod_charge);
            }
            $("#btnPlaceOrder").prop("disabled", false);
        } else {
            netTotal = parseInt(cartTotal) - parseInt(discount_amt);
        }

        if (freeDeivery == 1) {
            $(".content").hide();
            $("#shipping_online").html('Free');
            $("#shipping_cod").html('Free');
        } else {
            $("#shipping_online").html('₹' + tot_shipping_charge);
            $("#shipping_cod").html('₹' + tot_cod_charge);
            $("#hdShipping_online").val(tot_shipping_charge);
            $("#hdShipping_cod").val(tot_cod_charge);
        }

        $("#total").html('₹' + parseInt(netTotal));
        $("#hdtotal_amount").val(parseInt(netTotal));
        $("#hdtotal_Cart").val(parseInt(netTotal));
    }

    function couponcalculation() {
        $.ajax({
            type: 'POST',
            url: '/coupon/discount',
            dataType: 'json',
            data: {
                "coupon_code": $("#coupon_code").val()
            },
            success: function(data) {
                $("#hddiscount").val(data.discount_percentage);
                $("#hddiscount").html(data.discount_percentage);
                $("#hdcoupon_id").val(data.coupon_id);
                $("#collectcash_amount").val("");
                calcmargin();
                bindShippingCost();

                if (data.discount_percentage) {
                    $("#coupon_status").html(data.success);
                    $("#coupon_status").removeClass("text-danger");
                    $("#coupon_status").addClass("text-success");
                    $(".order-discount").removeClass("hidden");
                } else {
                    $("#coupon_status").html(data.error);
                    $("#coupon_status").removeClass("text-success");
                    $("#coupon_status").addClass("text-danger");
                    $(".order-discount").addClass("hidden");
                }
            }
        });

    }
</script>
