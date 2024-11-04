
@extends('admin.admin_master')
@section('admin')
@section('title')
Product Stock Maintenance List 
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="modal fade" id="stockUpdateModal" tabindex="-1" aria-labelledby="stockUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="{{ route('product.update.variant.stock') }}" method="POST" enctype="multipart/form-data" name="product"
                id="product">
                @csrf
                <input type="hidden" name="hdProductId" id="hdProductId" value="">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Update Stock</h5>
                    </div>
                    <div class="card-body">
                        <!-- Add your form fields here -->
                        <h6 class="mb-1">Product Stock</h6>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12">
                                <div class="form-group">
                                    <input type="text" id="stock" name="stock" value="{{ old('stock') }}"
                                        class="form-control" title="Please enter Current Stock" autocomplete="off"
                                        />
                                    @error('stock')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <h6 class="mb-1">Varinat Stock</h6>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-6" id="divVariantSize">
                                <div class="form-group">
                                    <label>
                                        Size
                                    </label>
                                    <input type="text" control="int" class="form-control" autocomplete="off"
                                        id="ddlVariantSize" name="ddlVariantSize"
                                        value="{{ old('ddlVariantSize') }}" />
                                    @error('ddlVariantSize')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-6" id="divVariantStock">
                                <div class="form-group">
                                    <label>
                                        Stock 
                                    </label>
                                    <input type="number" id="ddlVariantStock" name="ddlVariantStock[]" min="0"
                                        value="{{ old('ddlVariantStock') }}" class="form-control" autocomplete="off" />
                                    @error('ddlVariantStock')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mb-3">
                    <button type="submit" class="btn btn-success w-sm">Update</button>
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
              <h5>Product Stock Maintenance List </h5>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Master</li>
                <li class="breadcrumb-item active">Product Stock Maintenance List</li>
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
                        <div class="col-md-4">
                            <div>
                                <label for="Category">Category</label>
                                <select id="category_id" name="category_id" class="form-control">
                                    <option value="0">Choose Category</option>
                                    @foreach ($categories as $categorie)
                                        <option value="{{ $categorie->id }}">{{ $categorie->category_name }}</option>
                                    @endforeach
                                </select><br>
                            </div>
                        </div>
                        <div class="col-md-8">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="tblProductMaintenanceList" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image </th>
                                        <th>Product</th>
                                        <th>Product SKU</th>
                                        <th>MRP</th>
                                        <th>Customer Price</th>
                                        <th>Reseller Price</th>
                                        <th>Product Stock Update</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody id="tbodyProductMaintenanceList">
                                    
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
<script src="{{ asset('backend/assets/js/product/product_stock_list.js?v=3') }}"></script>
