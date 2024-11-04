@extends('admin.admin_master')
@section('admin')
@section('title')
    Reseller Order List
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Reseller Order List </h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Master</li>
                        <li class="breadcrumb-item active">Reseller Order List</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3 ">
                            <label for="datefilter">Date Filter </label>
                            <input type="date" id="datefilter" class="form-control">
                        </div>
                        <div class="col-md-2 mb-3 mt-4 p-2 float-right">
                            <button type="button" class="btn btn-secondary clear-btn">Clear</button>
                        </div>
                        <div class="col-md-5"></div>
                        <div class="col-md-2">
                            <div class="mt-4 p-2">
                                <a id="export-excel" class="btn btn-success" href="{{ route('order_export') }}">
                                    Export to Excel
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tblAllSellerOrder" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Order Date</th>
                                            <th>Order No</th>
                                            <th>Reseller Name</th>
                                            <th style="display: none;">R Phone</th>
                                            <th>Order To</th>
                                            <th style="display: none;">C Phone</th>
                                            <th>Qty</th>
                                            <th>Sub Total</th>
                                            <th>Discount</th>
                                            <th>Shipping</th>
                                            <th>Margin</th>
                                            <th>Net Amount</th>
                                            <th>Delivery Status</th>
                                            <th>Payment Status</th>
                                            <th>Payment Method</th>
                                            <th>Shipping Label</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyList">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="logo"
    value="@if (Auth::user()->profile_photo_path == null) {{ asset('admin_profile.png') }} 
              @else {{ asset('upload/admin_images/' . Auth::user()->profile_photo_path) }} @endif">
@endsection
<script>
    var exportURL = '{{ route('order_export') }}';
</script>
<script src="{{ asset('backend/assets/js/common/jquery.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/common/common.js') }}"></script>
<script src="{{ asset('backend/assets/js/Reseller_order/seller_all_orders.js?v4') }}"></script>
