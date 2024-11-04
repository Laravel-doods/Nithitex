@extends('admin.admin_master')
@section('admin')
@section('title')
    Product View Analytics
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Product View Analytics </h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Master</li>
                        <li class="breadcrumb-item active">Product View Analytics</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="tblViewAnalytics" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Image </th>
                                        <th>Product Sku</th>
                                        <th>Total Views</th>
                                        <th>Product Stock</th>
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
<input type="hidden" id="logo"
    value="@if (Auth::user()->profile_photo_path == null) {{ asset('admin_profile.png') }} 
              @else {{ asset('upload/admin_images/' . Auth::user()->profile_photo_path) }} @endif">
<script src="{{ asset('backend/assets/js/common/jquery.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/common/common.js') }}"></script>
<script src="{{ asset('backend/assets/js/product/view_analytics.js') }}"></script>
@endsection