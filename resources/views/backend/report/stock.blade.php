@extends('admin.admin_master')
@section('admin')
@section('title')
    Product Stock Report
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Product Stock Report </h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Master</li>
                        <li class="breadcrumb-item active">Product Stock Report</li>
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
                        <div class="col-md-6">
                            <div>
                                <label for="Category">Category</label>
                                <select id="category_id" name="category_id" class="form-control">
                                    <option value="">Choose Category</option>
                                    @foreach ($categories as $categorie)
                                        <option value="{{ $categorie->id }}">{{ $categorie->category_name }}</option>
                                    @endforeach
                                </select><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="ddType"> Select Type </label>
                                <select id="ddType" name="ddType" class="form-control" title="Please Select Size">
                                    <option value="1">Product</option>
                                    <option value="2">Variant</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="dataTablestock" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Image </th>
                                        <th>Product Sku</th>
                                        <th>MRP</th>
                                        <th>Customer Price</th>
                                        <th>Reseller Price</th>
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
<script src="{{ asset('backend/assets/js/product/product_stock_report.js?v=1') }}"></script>
@endsection
