@extends('admin.admin_master')
@section('admin')
@section('title')
Product List 
@endsection

<div class="modal fade" id="addImageModal" tabindex="-1" aria-labelledby="addImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('add.product.image') }}" method="POST" enctype="multipart/form-data" name="product"
                id="product">
                @csrf
                <input type="hidden" name="hdProductId" id="hdProductId" value="">
                <input type="hidden" name="product_sku" id="product_sku" value="">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Gallery</h5>
                    </div>
                    <div class="card-body">
                        <!-- Add your form fields here -->
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-xs-8">
                                <label>
                                    Product Images<span class="text-danger">*</span>
                                    <div class="text-muted"><small>(Multiple images allowed)</small></div>
                                </label>
                                <div class="form-group">
                                    <div class="upload__box">
                                        <div class="upload__btn-box">
                                            <label class="upload__btn">
                                                <p>Upload images</p>
                                                <input type="file" id="multiImg" name="multi_img[]"
                                                    value="{{ old('multi_img[]') }}" multiple=""
                                                    data-max_length="20"
                                                    class="form-control upload__inputfile" required>
                                            </label>
                                            @error('multi_img')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="upload__img-wrap"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-4">
                                <div class="form-group">
                                    <label>
                                        Product Video Link
                                    </label>
                                    <input type="text" id="video_link" name="video_link"
                                        value="{{ old('video_link') }}" class="form-control">
                                    @error('video_link')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mb-3">
                    <button type="submit" class="btn btn-success w-sm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h5>Product List </h5>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Master</li>
                <li class="breadcrumb-item active">Product List</li>
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
                            <table id="tblProductList" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image </th>
                                        <th>Product</th>
                                        <th>Product SKU</th>
                                        <th>MRP</th>
                                        <th>Customer Price</th>
                                        <th>Reseller Price</th>
                                        <th>Discount (Customer/Reseller)</th>
                                        <th>Avl Quantity</th>
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
<input type="hidden" id="logo"
    value="@if (Auth::user()->profile_photo_path == null) {{ asset('admin_profile.png') }} 
              @else {{ asset('upload/admin_images/' . Auth::user()->profile_photo_path) }} @endif">
@endsection
<script src="{{ asset('backend/assets/js/common/jquery.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/common/common.js') }}"></script>
<script src="{{ asset('backend/assets/js/product/product-list.js?v=5') }}"></script>