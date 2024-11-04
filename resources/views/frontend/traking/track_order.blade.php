@php
    $url = 'frontend.main_master';
    $seller_url = '';
    if (Auth::check()) {
        if (Auth::user()->userrole_id == 2) {
            $url = 'seller.seller_main_master';
            $seller_url = 'seller.';
        }
    }
@endphp
@extends($url)
@section('content')

@section('title')
    Order Traking Page
@endsection

<style type="text/css">
    .card {
        /* font-family: 'Poppins', sans-serif; */
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 0.10rem
    }

    .card-header:first-child {
        border-radius: calc(0.37rem - 1px) calc(0.37rem - 1px) 0 0
    }

    .card-header {
        padding: 0.75rem 1.25rem;
        margin-bottom: 0;
        background-color: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1)
    }

    .track {
        position: relative;
        background-color: #f7be16;
        height: 7px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        margin-bottom: 60px;
        margin-top: 50px;
    }

    .track .step {
        -webkit-box-flex: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;
        width: 25%;
        margin-top: -18px;
        text-align: center;
        position: relative;
        min-width: 100px;
    }

    .track .step.active:before {
        background: #27aa80
    }

    .track .step::before {
        height: 7px;
        position: absolute;
        content: "";
        width: 100%;
        left: 0;
        top: 18px
    }

    .track .step.active .icon {
        background: #27aa80;
        color: #fff;
    }

    .track .icon {
        display: inline-block;
        width: 40px;
        height: 40px;
        line-height: 40px;
        position: relative;
        border-radius: 100%;
        background: #f7be16
    }

    .track .step.active .text {
        font-weight: 400;
        color: #000
    }

    .track .text {
        display: block;
        margin-top: 7px
    }

    .itemside {
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        width: 100%
    }

    .itemside .aside {
        position: relative;
        -ms-flex-negative: 0;
        flex-shrink: 0
    }

    .img-sm {
        width: 80px;
        height: 80px;
        padding: 7px
    }

    ul.row,
    ul.row-sm {
        list-style: none;
        padding: 0
    }

    .itemside .info {
        padding-left: 15px;
        padding-right: 7px
    }

    .itemside .title {
        display: block;
        margin-bottom: 5px;
        color: #27aa80
    }

    p {
        margin-top: 0;
        margin-bottom: 1rem
    }

    .btn-warning {
        color: #ffffff;
        background-color: #27aa80;
        border-color: #27aa80;
        border-radius: 1px
    }

    .btn-warning:hover {
        color: #ffffff;
        background-color: #27aa80;
        border-color: #27aa80;
        border-radius: 1px
    }
</style>
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">My Orders / Tracking</li>
            </ul>
        </div>
    </div>
</div>
<div class="order-tracking-area pt-110 pb-120">
    <div class="container">
        <article class="card">
            <div class="card-body">
                <div class="row" style="margin-left: 30px; margin-top: 20px;">
                    <div class="col-md-2">
                        @if ($track->invoice_no)
                            <b> Invoice Number </b><br>
                            {{ $track->invoice_no }}
                        @else
                            <b> Order Number </b><br>
                            {{ $track->order_number }}
                        @endif
                    </div> <!-- // end col md 2 -->

                    <div class="col-md-2">
                        <b> Order Date </b><br>
                        {{ $track->order_date }}
                    </div> <!-- // end col md 2 -->

                    <div class="col-md-2">
                        <b> Shipped To - {{ $track->name }} </b><br>
                        {{ $track->city_name }} / {{ $track->state_name }}
                    </div> <!-- // end col md 2 -->

                    <div class="col-md-2">
                        <b> Mobile Number </b><br>
                        {{ $track->phone }}
                    </div> <!-- // end col md 2 -->

                    <div class="col-md-2">
                        <b> Payment Method </b><br>
                        {{ $track->payment_type }}
                    </div> <!-- // end col md 2 -->

                    <div class="col-md-2">
                        <b> Total Amount </b><br>
                        ₹{{ round($track->amount) }}
                    </div> <!-- // end col md 2 -->

                </div> <!-- // end row   -->


                <div class="table-responsive ">
                    <div class="track">
                        @if ($track->status == 'pending')
                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Pending</span>
                            </div>
                            <div class="step">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text"> Order Confirmed</span>
                            </div>
                        @elseif($track->status == 'confirmed')
                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Pending</span>
                            </div>

                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text"> Confirmed</span>
                            </div>

                            <div class="step">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text"> Processing </span>
                            </div>

                            <div class="step">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Picked</span>
                            </div>

                            <div class="step">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Shipped </span>
                            </div>

                            <div class="step">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Delivered </span>
                            </div>
                        @elseif($track->status == 'processing')
                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Pending</span>
                            </div>

                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text"> Confirmed</span>
                            </div>

                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text"> Processing </span>
                            </div>

                            <div class="step">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Picked</span>
                            </div>

                            <div class="step">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Shipped </span>
                            </div>

                            <div class="step">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Delivered </span>
                            </div>
                        @elseif($track->status == 'picked')
                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Pending</span>
                            </div>

                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text"> Confirmed</span>
                            </div>

                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text"> Processing </span>
                            </div>

                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Picked</span>
                            </div>

                            <div class="step">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Shipped </span>
                            </div>

                            <div class="step">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Delivered </span>
                            </div>
                        @elseif($track->status == 'shipped')
                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Pending</span>
                            </div>

                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text"> Confirmed</span>
                            </div>

                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text"> Processing </span>
                            </div>

                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Picked</span>
                            </div>

                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Shipped </span>
                            </div>

                            <div class="step">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Delivered </span>
                            </div>
                        @elseif($track->status == 'delivered')
                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Pending</span>
                            </div>

                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text"> Confirmed</span>
                            </div>

                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text"> Processing </span>
                            </div>

                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Picked</span>
                            </div>

                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Shipped </span>
                            </div>

                            <div class="step active">
                                <span class="icon"> <i class="fa fa-check"></i> </span>
                                <span class="text">Delivered </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </article>
    </div>
</div>





@endsection
