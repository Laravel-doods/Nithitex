@extends('admin.admin_master')
@section('admin')
@section('title')
    Order Details
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- <h5>Order List </h5> --}}
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Master</li>
                        <li class="breadcrumb-item active">Order Details</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order Details</h3>
                    <a href="{{ route('seller.order.all') }}" class="btn-dark btn-md btn" style="float: right;">Back</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('seller.order.update') }}" method="POST" id="payment-form">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ asset('frontend/assets/images/logo/logo.png') }}" class="img-responsive"
                                    alt="" />
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                            
                                    @php
                                        // Define available statuses based on current status
                                        $statusOptions = [
                                            'pending' => ['pending', 'confirmed', 'processing', 'picked', 'shipped', 'delivered', 'cancelled'],
                                            'confirmed' => ['confirmed', 'processing', 'picked', 'shipped', 'delivered', 'cancelled'],
                                            'processing' => ['processing', 'picked', 'shipped', 'delivered', 'cancelled'],
                                            'picked' => ['picked', 'shipped', 'delivered', 'cancelled'],
                                            'shipped' => ['shipped', 'delivered', 'cancelled'],
                                            'delivered' => ['delivered', 'shipped'],
                                            'cancelled' => ['cancelled'],
                                            'returned' => ['returned', 'returned_to_office'],
                                            'returned_to_office' => ['return to office'],
                                        ];
                                    @endphp
                            
                                    <select name="order_status" id="order_status" class="form-control order_status">
                                        @foreach ($statusOptions[$order->status] as $status)
                                            <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                            
                                    @error('order_status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                            
                                    <input type="hidden" id="hidstatus" value="{{ $order->status }}">
                                    <input type="hidden" id="hidpaystatus" value="{{ $order->payment_status }}">
                                    <input type="hidden" id="tracking_no" value="{{ $order->track_number }}">
                                </div>
                            
                                <div style="display: none" class="trackno form-group">
                                    <input type="text" id="track_no" name="track_no" class="form-control"
                                           placeholder="Enter Order Tracking No" value="{{ $order->track_number }}" autocomplete="off">
                                    @error('track_no')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                            
                                    <input type="text" id="track_url" name="track_url" class="form-control"
                                           placeholder="Enter Order Tracking Url" value="{{ $order->track_url }}" autocomplete="off">
                                    @error('track_url')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            
                                <div class="col-md-1 apply-submit d-flex justify-content-right" style="display: none">
                                    <button type="submit" class="btn btn-success btn-flat" 
                                            @if (in_array($order->status, [ 'cancelled', 'returned_to_office'])) disabled @endif>
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">

                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div>
                                    <h6 class="pr-5"><span>Order No : </span>{{ $order->order_number }}</h6>
                                </div>
                                @if ($order->invoice_no)
                                    <div>
                                        <h6 class="pr-5"><span>Invoice No : </span>{{ $order->invoice_no }}</h6>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row mt-3">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <b>
                                    <h5>Billing Address</h5>
                                </b>
                                <p class="font">
                                    <strong>Name:</strong> {{ $order->user->name }}<br>
                                    <strong>Email:</strong> {{ $order->user->email }} <br>
                                    <strong>Phone:</strong> {{ $order->user->phone }} <br>

                                </p>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4" style="float-right;">
                                <b>
                                    <h5>Shipping Address</h5>
                                </b>
                                <p class="font">
                                    <strong>Name:</strong> {{ $order->name }}<br>
                                    <strong>Phone:</strong> {{ $order->phone }} <br>
                                    @if ($order->alternative_number)
                                        <strong>Alternative Number:</strong> {{ $order->alternative_number }} <br>
                                    @endif
                                    <strong>Address:</strong>
                                    {{ $order->door_no }},{{ $order->street_address }}.{{ $order->city_name }}.{{ $order->state_name }}
                                    <br>
                                    <strong>Post Code:</strong> {{ $order->pin_code }}
                                </p><br>
                                @if ($order->status == 'returned_to_office')
                                    <strong>Returned at Office:</strong> {{ \Carbon\Carbon::parse($order->returned_at_office)->format('d-m-Y h:i a') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <h6 class="mb-3 mt-5" style="color: green;">Order Summary</h6>
                    <div class="row">
                        <div class="table-responsive col-lg-12 col-md-12 col-sm-12">
                            <table class="table" width="100%">
                                <thead style="background-color: green; color:#FFFFFF;">
                                    <tr>
                                        <th style="color:#FFF;">Image</th>
                                        <th style="color:#FFF;">Product Name</th>
                                        <th style="color:#FFF;">Quantity</th>
                                        <th style="color:#FFF;">Price</th>
                                        <th style="color:#FFF;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderItem as $item)
                                        <tr>
                                            <td><label for=""><img
                                                        src="{{ asset($item->product->product_image) }}"
                                                        height="50px;" width="50px;"> </label></td>
                                            <td>
                                                <label for=""> {{ $item->product->product_name }}</label>
                                                <br>
                                                @if ($item->variant_size != null)
                                                    <div class="mt-2 text-muted">
                                                        Size: {{ $item->variant_size }}
                                                    </div>
                                                @endif
                                                @if ($item->product->color_id != null)
                                                    <div class="mt-2 text-muted">
                                                        color: {{ $item->product->color->color_name }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td><label for=""> {{ $item->qty }}</label></td>
                                            <td><label for=""> ₹{{ round($item->price) }} </label></td>
                                            <td><label for=""> ₹{{ round($item->price / $item->qty) }}
                                                </label></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row mt-3">
                        <div class="col-lg-9 col-md-9 col-sm-9">

                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3" style="float-right;">
                            <p class="font">
                            <h5><span style="color: green;">Subtotal:</span><span
                                    style="font-family: DejaVu Sans; sans-serif;">
                                    &#8377;</span>{{ $order->sub_total }}</h5>
                            @if ($order->coupon_discount)
                                <h5><span style="color: green;">Discount
                                        ({{ $order->couponCode->discount_percentage }}%):</span><span
                                        style="font-family: DejaVu Sans; sans-serif;">
                                        &#8377;</span>{{ $order->coupon_discount }}</h5>
                            @endif
                            @if ($order->referral_discount)
                                <h5><span style="color: green;">Referral Discount:
                                    </span><span style="font-family: DejaVu Sans; sans-serif;">
                                        &#8377;</span>{{ $order->referral_discount }}</h5>
                            @endif
                            <h5><span style="color: green;">Shipping Charge:</span><span
                                    style="font-family: DejaVu Sans; sans-serif;">
                                    &#8377;</span>{{ $order->shipping_charge }}</h5>
                            <h5><span style="color: green;">Margin:</span><span
                                    style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>
                                @if ($order->margin_amount)
                                    {{ $order->margin_amount }}
                                @else
                                    0
                                @endif
                            </h5>
                            <h5><span style="color: green;">Total:</span><span
                                    style="font-family: DejaVu Sans; sans-serif;"> &#8377;</span>{{ $order->amount }}
                            </h5>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var dropdownFieldValue = $("#hidstatus").val();
        var paydropdownFieldValue = $("#hidpaystatus").val();
        var tracking_number = $("#tracking_no").val();

        var track_no = $("#track_no").val();
        var track_url = $("#track_url").val();

        if (dropdownFieldValue == "shipped") {
            $('.trackno').show();
            $('#track_no').val(tracking_number);
        } else {
            $('.trackno').hide();
        }
        $('#order_status').on('change', function() {
            if ($('#order_status option:selected').val() == "shipped") {
                $('.trackno').show();
                $('#track_no').val(tracking_number);
            } else {
                $('.trackno').hide();
            }
        });
    });
</script>
