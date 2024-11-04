@extends('seller.seller_main_master')
@section('content')

    <div class="breadcrumb-area bg-gray">
        <div class="container">
            <div class="breadcrumb-content text-center">
                <ul>
                    <li>
                        <a href="{{ route('seller.home') }}">Home</a>
                    </li>
                    <li class="active">Return Order </li>
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
                                @if ($orders->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Order Date</th>
                                                    <th>Order No</th>
                                                    <th>Qty</th>
                                                    <th>Sub Total</th>
                                                    <th>Shipping</th>
                                                    <th>Total</th>
                                                    <th>Payment Mode</th>
                                                    <th>Return Reason</th>
                                                    <th>Return Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $serialNo = 1;
                                                @endphp
                                                @foreach ($orders as $order)
                                                    <tr>
                                                        <td>{{ $serialNo }}</td>
                                                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                        <td>
                                                            {{ $order->order_number }}
                                                            @if ($order->invoice_no)
                                                                <div class="text-muted bg-light p-1">
                                                                    <small>{{ $order->invoice_no }}</small>
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td>{{ $order->tot_Qty }}</td>
                                                        <td>₹{{ round($order->sub_total) }}</td>
                                                        <td>₹{{ round($order->shipping_charge) }}</td>
                                                        <td>₹{{ round($order->amount) }}</td>
                                                        <td>{{ $order->payment_type }}</td>
                                                        <td class="col-md-2">
                                                            <label for=""> {{ $order->return_reason }}</label>
                                                        </td>
                                                        <td class="col-md-2">
                                                            <label for="">
                                                                @if ($order->return_order == 0)
                                                                    <span class="badge badge-pill badge-warning"
                                                                        style="background: #418DB9;"> No Return Request
                                                                    </span>
                                                                @elseif($order->return_order == 1)
                                                                    <span class="badge badge-pill badge-warning"
                                                                        style="background: #800000;"> Pending </span>
                                                                    <span class="badge badge-pill badge-warning"
                                                                        style="background:red;">Return Requested </span>
                                                                @elseif($order->return_order == 2)
                                                                    <span class="badge badge-pill badge-warning"
                                                                        style="background: #008000;">Approved </span>
                                                                @elseif($order->return_order == 3)
                                                                    <span class="badge badge-pill badge-warning"
                                                                        style="background: red;">Rejected </span>
                                                                @endif

                                                            </label>
                                                        </td>

                                                    </tr>
                                                    @php
                                                        $serialNo++;
                                                    @endphp
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('frontend/assets/images/bg/empty_results.png') }}">
                                    </div>
                                @endif
                            </div> <!-- My Account Tab Content End -->
                        </div>
                    </div> <!-- My Account Page End -->
                </div>
            </div>
        </div>
    </div>
    <!-- my account wrapper end -->
@endsection
