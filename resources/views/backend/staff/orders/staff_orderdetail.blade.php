@extends('admin.admin_master')
@section('admin')
@section('title')
    Staff Order Details
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
                        <li class="breadcrumb-item active">Staff</li>
                        <li class="breadcrumb-item active">Staff Order Details</li>
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
                    <a href="{{ route('staff.order.all') }}" class="btn-dark btn-md btn" style="float: right;">Back</a>
                </div>
                <div class="card-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <ul class="p-0 m-0" style="list-style: none;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('stafforder.update') }}" method="POST" id="payment-form">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ asset('frontend/assets/images/logo/logo.png') }}" class="img-responsive"
                                    alt="" />
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="hidden" name="order_id" value="{{ $stafforder->id }}">
                                    <select name="order_status" id="order_status" class="form-control order_status">
                                        @if ($stafforder->status == 'pending')
                                            <option {{ $stafforder->status == 'pending' ? 'selected' : '' }}
                                                value="pending">
                                                Pending</option>
                                            <option {{ $stafforder->status == 'confirmed' ? 'selected' : '' }}
                                                value="confirmed">Confirmed</option>
                                            <option {{ $stafforder->status == 'processing' ? 'selected' : '' }}
                                                value="processing">Processing</option>
                                            <option {{ $stafforder->status == 'picked' ? 'selected' : '' }}
                                                value="picked">
                                                Picked</option>
                                            <option {{ $stafforder->status == 'shipped' ? 'selected' : '' }}
                                                value="shipped">
                                                Shipped</option>
                                            <option {{ $stafforder->status == 'delivered' ? 'selected' : '' }}
                                                value="delivered">Delivered</option>
                                            <option {{ $stafforder->status == 'cancelled' ? 'selected' : '' }}
                                                value="cancelled">Cancelled</option>
                                        @endif
                                        @if ($stafforder->status == 'confirmed')
                                            <option {{ $stafforder->status == 'confirmed' ? 'selected' : '' }}
                                                value="confirmed">Confirmed</option>
                                            <option {{ $stafforder->status == 'processing' ? 'selected' : '' }}
                                                value="processing">Processing</option>
                                            <option {{ $stafforder->status == 'picked' ? 'selected' : '' }}
                                                value="picked">
                                                Picked</option>
                                            <option {{ $stafforder->status == 'shipped' ? 'selected' : '' }}
                                                value="shipped">
                                                Shipped</option>
                                            <option {{ $stafforder->status == 'delivered' ? 'selected' : '' }}
                                                value="delivered">Delivered</option>
                                        @endif
                                        @if ($stafforder->status == 'processing')
                                            <option {{ $stafforder->status == 'processing' ? 'selected' : '' }}
                                                value="processing">Processing</option>
                                            <option {{ $stafforder->status == 'picked' ? 'selected' : '' }}
                                                value="picked">
                                                Picked</option>
                                            <option {{ $stafforder->status == 'shipped' ? 'selected' : '' }}
                                                value="shipped">
                                                Shipped</option>
                                            <option {{ $stafforder->status == 'delivered' ? 'selected' : '' }}
                                                value="delivered">Delivered</option>
                                        @endif
                                        @if ($stafforder->status == 'picked')
                                            <option {{ $stafforder->status == 'picked' ? 'selected' : '' }}
                                                value="picked">
                                                Picked</option>
                                            <option {{ $stafforder->status == 'shipped' ? 'selected' : '' }}
                                                value="shipped">
                                                Shipped</option>
                                            <option {{ $stafforder->status == 'delivered' ? 'selected' : '' }}
                                                value="delivered">Delivered</option>
                                        @endif
                                        @if ($stafforder->status == 'shipped')
                                            <option {{ $stafforder->status == 'shipped' ? 'selected' : '' }}
                                                value="shipped">
                                                Shipped</option>
                                            <option {{ $stafforder->status == 'delivered' ? 'selected' : '' }}
                                                value="delivered">Delivered</option>
                                        @endif
                                        @if ($stafforder->status == 'delivered')
                                            <option {{ $stafforder->status == 'delivered' ? 'selected' : '' }}
                                                value="delivered">Delivered</option>
                                        @endif
                                        @if ($stafforder->status == 'cancelled')
                                            <option {{ $stafforder->status == 'cancelled' ? 'selected' : '' }}
                                                value="cancelled">Cancelled</option>
                                        @endif
                                    </select>

                                    @error('order_status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                    <input type="hidden" id="hidstatus" value="{{ $stafforder->status }}" />
                                    <input type="hidden" id="hidpaystatus"
                                        value="{{ $stafforder->payment_status }}" />
                                    <input type="hidden" id="tracking_no" value="{{ $stafforder->track_number }}">
                                </div>

                                <div style="display: none" class="trackno form-group">
                                    <input type="text" id="track_no" name="track_no" class="form-control"
                                        title="Enter Order Tracking NO" placeholder="Enter Order Tracking No"
                                        autocomplete="off" value="{{ $stafforder->track_number }}" />
                                    @error('track_no')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-1 apply-submit d-flex justify-content-right" style="display: none">
                                    <button type="submit" class="btn btn-success btn-flat" style="float: right;"
                                        @if ($stafforder->status == 'delivered' || $stafforder->status == 'cancelled') disabled @endif>Apply</button>
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
                                    <h6 class="pr-5"><span>Order No : </span>{{ $stafforder->order_number }}</h6>
                                </div>
                                @if ($stafforder->invoice_no)
                                    <div>
                                        <h6 class="pr-5"><span>Invoice No : </span>{{ $stafforder->invoice_no }}
                                        </h6>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row mt-3">
                            <div class="col-lg-8 col-md-8 col-sm-8">

                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4" style="float-right;">
                                <b>
                                    <h5>Order By</h5>
                                </b>
                                <p class="font">
                                    <strong>Name:</strong> {{ $stafforder->name }}<br>
                                    <strong>Email:</strong> {{ $stafforder->email }} <br>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <h6 class="mb-3 mt-5" style="color: green;">Order Summary</h6>
                    <div class="row">
                        <div class="table-responsive col-lg-12 col-md-12 col-sm-12">
                            <table class="table">
                                <thead style="background-color: green;">
                                    <tr>
                                        <th style="color:#FFF;">Image</th>
                                        <th style="color:#FFF;">Product Name</th>
                                        <th style="color:#FFF;">Quantity</th>
                                        <th style="color:#FFF;">Price</th>
                                        <th style="color:#FFF;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($staffOrderItem as $item)
                                        <tr>
                                            <td><img src="{{ asset($item->product->product_image) }}" height="50px;"
                                                    width="50px;"></td>
                                            <td> 
                                                {{ $item->product->product_name }}
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
                                            <td> {{ $item->qty }}</td>
                                            <td> ₹{{ round($item->price) }} </td>
                                            <td> ₹{{ round($item->price / $item->qty) }} </td>
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
                            <h5><span style="color: green;">Total:</span><span
                                    style="font-family: DejaVu Sans; sans-serif;">
                                    &#8377;</span>{{ $stafforder->amount }}
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
