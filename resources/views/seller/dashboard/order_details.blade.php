@extends('seller.seller_main_master')
@section('content')

    <div class="breadcrumb-area bg-gray">
        <div class="container">
            <div class="breadcrumb-content text-center">
                <ul>
                    <li>
                        <a href="{{ route('seller.home') }}">Home</a>
                    </li>
                    <li class="active">My Order </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- my account wrapper start -->
    <div class="my-account-wrapper pt-120 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- My Account Page Start -->
                    <div class="myaccount-page-wrapper">
                        <!-- My Account Tab Menu Start -->
                        <div class="row">
                            @include('seller.seller_sidebar')
                            <!-- My Account Tab Menu End -->
                            <!-- My Account Tab Content Start -->
                            <div class="col-lg-10 col-md-10">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <img src="{{ asset('frontend/assets/images/logo/nithitex-logo-large.png') }}"
                                            class="img-responsive" alt="" width="60%" />
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <h6 class="pr-5"><span>Order No : </span>{{ $order->order_number }}</h6>
                                        </div>
                                        @if ($order->invoice_no)
                                            <div class="d-flex align-items-center justify-content-end">
                                                <h6 class="pr-5"><span>Invoice No : </span>{{ $order->invoice_no }}</h6>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="container">
                                    <h5 class="mb-3 mt-5" style="color: green;">Order Summary</h5>
                                    <div class="row mt-3">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <table class="table">
                                                <thead class="bg-secondary text-white">
                                                    <tr>
                                                        <th>Image</th>
                                                        <th>Product Name</th>
                                                        <th>Quantity</th>
                                                        <th>Price</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($orderItem as $item)
                                                        <tr>
                                                            <td><label for=""><img class="rounded-circle"
                                                                        src="{{ asset($item->product->product_image) }}"
                                                                        height="50px;" width="50px;"> </label>
                                                            </td>
                                                            <td>
                                                                <label for="">
                                                                    {{ $item->product->product_name }} 
                                                                   
                                                                </label>
                                                                <div class="text-white ">
                                                                    @if ($item->variant_size != null)
                                                                        <small class="bg-secondary rounded px-2">size: {{ $item->variant_size }}</small>
                                                                    @endif
                                                                    @if ($item->product->color_id != null )
                                                                        {{-- <br> --}}
                                                                        <small class="bg-secondary rounded px-2">color: {{ $item->product->color->color_name }}</small>
                                                                    @endif
                                                                    {{-- <br> --}}
                                                                    <small class="bg-secondary rounded px-2"> {{ $item->product->product_sku }}</small>
                                                                </div>
                                                               
                                                            </td>
                                                            <td><label for=""> {{ $item->qty }}</label></td>
                                                            <td><label for="">
                                                                    ₹{{ round($item->price / $item->qty) }} </label></td>
                                                            <td><label for=""> ₹{{ round($item->price) }} </label>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="container">
                                    <div class="row mt-3">
                                        <div class="col-lg-8 col-md-8 col-sm-8">

                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4" style="float-right;">
                                            <p class="font">
                                            <h5><span style="color: green;">Subtotal:</span><span
                                                    style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ $order->sub_total }}
                                            </h5>
                                            @if ($order->coupon_discount)
                                                <h5><span style="color: green;">Discount
                                                        ({{ $order->couponCode->discount_percentage }}%):
                                                    </span><span
                                                        style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ round($order->coupon_discount) }}
                                                </h5>
                                            @endif
                                            <h5><span style="color: green;">Shipping Charge:</span><span
                                                    style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ $order->shipping_charge }}
                                            </h5>
                                            @if ($order->margin_amount)
                                                <h5><span style="color: green;">Margin Amount:</span><span
                                                        style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ $order->margin_amount }}
                                                </h5>
                                            @endif
                                            <h5><span style="color: green;">Total:</span><span
                                                    style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ $order->amount }}
                                            </h5>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="container">
                                    <div class="row mt-3">
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <b>Order Details:</b>
                                            <p class="font">
                                                <strong>Payment Type:</strong> {{ $order->payment_type }} <br>
                                                <strong>Payment Status:</strong> {{ $order->payment_status }} <br>
                                                @if ($order->r_payment_id)
                                                    <strong>Transaction ID:</strong> {{ $order->r_payment_id }} <br>
                                                @endif
                                                <strong>Order Status:</strong> <br><label for="">

                                                    @if ($order->status == 'pending')
                                                        <span class="badge badge-pill badge-warning"
                                                            style="background: #800080;"> Pending </span>
                                                    @elseif($order->status == 'confirmed')
                                                        <span class="badge badge-pill badge-warning"
                                                            style="background: #0000FF;"> Confirm </span>
                                                    @elseif($order->status == 'processing')
                                                        <span class="badge badge-pill badge-warning"
                                                            style="background: #FFA500;"> Processing </span>
                                                    @elseif($order->status == 'picked')
                                                        <span class="badge badge-pill badge-warning"
                                                            style="background: #808000;"> Picked </span>
                                                    @elseif($order->status == 'shipped')
                                                        <span class="badge badge-pill badge-warning"
                                                            style="background: #808080;"> Shipped </span>
                                                    @elseif($order->status == 'delivered')
                                                        <span class="badge badge-pill badge-warning"
                                                            style="background: #008000;"> Delivered </span>
                                                    @elseif($order->return_order == 1)
                                                        <span class="badge badge-pill badge-warning"
                                                            style="background:red;">Return Requested </span>
                                                    @elseif($order->cancel_request == 1)
                                                        <span class="badge badge-pill badge-warning"
                                                            style="background:red;">Cancel Requested </span>
                                                    @else
                                                        <span class="badge badge-pill badge-warning"
                                                            style="background: #FF0000;"> {{ $order->status }} </span>
                                                    @endif
                                                </label><br>

                                            </p>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4" style="float-right;">
                                            <b>Shipping Address:</b>
                                            <p class="font">
                                                <strong>Name:</strong> {{ $order->name }}<br>
                                                <strong>Phone:</strong> {{ $order->phone }} <br>
                                                @if ($order->alternative_number)
                                                    <strong>Alternative Number:</strong> {{ $order->alternative_number }}
                                                    <br>
                                                @endif
                                                <strong>Address:</strong> {{ $order->door_no }},
                                                {{ $order->street_address }}, {{ $order->city_name }},
                                                {{ $order->state_name }} <br>
                                                <strong>Post Code:</strong> {{ $order->pin_code }}
                                            </p>
                                        </div>
                                    </div>
                                </div>


                                <div class="container">
                                    <div class="row mt-3">
                                        <div class="col-lg-4 col-md-4 col-sm-4" style="float-right;">
                                            @if ($order->status != 'cancelled')
                                                @if ($order->status != 'delivered' && $order->status != 'returned')
                                                    @php
                                                        $orders = App\Models\Order::where('id', $order->id)
                                                            ->where('cancel_request', 0)
                                                            ->first();
                                                    @endphp
                                                    @if ($orders)
                                                        <form action="{{ route('reseller.cancel.request', $order->id) }}"
                                                            method="post">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger">Cancel</button>
                                                        </form>
                                                    @else
                                                        @php
                                                            $cancel_order = App\Models\Order::where('id', $order->id)
                                                                ->where('cancel_request', 2)
                                                                ->first();
                                                        @endphp
                                                        @if ($cancel_order)
                                                            <span class="badge badge-pill badge-warning"
                                                                style="background: red">Your Order Has Been Cancelled</span>
                                                        @else
                                                            <span class="badge badge-pill badge-warning"
                                                                style="background: red">You Have sent cancel request for
                                                                this
                                                                product</span>
                                                        @endif
                                                    @endif
                                                @else
                                                    @php
                                                        $retorder = App\Models\Order::where('id', $order->id)
                                                            ->where('status', 'delivered')
                                                            ->first();
                                                    @endphp

                                                    @if ($retorder)
                                                        <form action=" {{ route('seller.return.order', $order->id) }}"
                                                            method="post">
                                                            @csrf

                                                            <div class="form-group">
                                                                <b> Order Return Reason:</b>
                                                                <textarea name="return_reason" id="Return Reason" placeholder="" class="form-control" cols="30" rows="05"
                                                                    maxlength="255" required></textarea>
                                                            </div><br>
                                                            <button type="submit" class="btn btn-danger">Order
                                                                Return</button>
                                                        </form>
                                                    @else
                                                        @php
                                                            $return_order = App\Models\Order::where('id', $order->id)
                                                                ->where('return_order', 2)
                                                                ->first();
                                                            $reject = App\Models\Order::where('id', $order->id)
                                                                ->where('return_order', 3)
                                                                ->first();
                                                        @endphp
                                                        @if ($return_order)
                                                            <span class="badge badge-pill badge-warning"
                                                                style="background: red">Your Order Has Been Returned</span>
                                                        @elseif(!$reject)
                                                            <span class="badge badge-pill badge-warning"
                                                                style="background: red">You Have send return request for
                                                                this
                                                                product</span>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endif

                                        </div>
                                    </div>
                                </div>


                            </div> <!-- My Account Tab Content End -->
                        </div>
                    </div> <!-- My Account Page End -->
                </div>
            </div>
        </div>
    </div>
    <!-- my account wrapper end -->
@endsection
