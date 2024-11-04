@extends('admin.admin_master')
@section('admin')
@section('title')
    Product
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Add New Products</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Master</li>
                        <li class="breadcrumb-item active">Add Product</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row">
        <div class="col-sm-12">
            {{-- <form method="post" action="{{ route('product.store') }}" enctype="multipart/form-data" id="productSubmit"> --}}
            <form method="post" action="{{ route('product.store') }}" enctype="multipart/form-data">
                @csrf

                <input type="hidden" id="hdProductVariant" name="hdProductVariant" value="0">
                <input type="hidden" id="hdVariantType" name="hdVariantType" value="0">
                <div class="card">
                    <div class="nav-tabs-custom card-box" id="divLed">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#information" data-toggle="tab">Product Information</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" href="#imagevideo" data-toggle="tab">Images & Videos</a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link" href="#pricestock" data-toggle="tab">Product Price</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#productVariant" data-toggle="tab">Variants</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#description" data-toggle="tab">Description &
                                    Specification</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#seo" data-toggle="tab">SEO</a>
                            </li>
                        </ul>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="show active tab-pane" id="information">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-4">
                                            <div class="form-group">
                                                <label for="txtDesignationName">
                                                    Product Name <span class="text-danger">*</span></label>
                                                <input type="text" id="product_name"
                                                    title="Please Enter Product Name" name="product_name"
                                                    value="{{ old('product_name') }}" placeholder="Enter Product Name"
                                                    class="form-control" autocomplete="off" required />
                                                @error('product_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-4">
                                            <div class="form-group">
                                                <label for="ddlMainCategoryType">Main Category Type <span
                                                        class="text-danger">*</span></label>
                                                <select id="ddlMainCategoryType" name="ddlMainCategoryType"
                                                    class="form-control" title="Please Select Main Category Type"
                                                    required>
                                                    <option value="0">Select Main Category</option>
                                                    @foreach ($main_category as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->main_category_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('ddlMainCategoryType')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-4">
                                            <div class="form-group">
                                                <label for="ddlCategoryType">Category Type <span
                                                        class="text-danger">*</span></label>
                                                <select id="ddlCategoryType" name="ddlCategoryType" class="form-control"
                                                    title="Please Select Category Type" required>
                                                    <option value="0">Select Category</option>
                                                </select>
                                                @error('ddlCategoryType')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-xs-4">
                                            <div class="form-group">
                                                <label>Tag</label>
                                                <input type="text" id="tags" name="tags"
                                                    value="{{ old('tags') }}" data-role="tagsinput"
                                                    class="form-control" width="100%">
                                                @error('tags')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-xs-4">
                                            <div class="form-group">
                                                <label>Current Stock <span class="text-danger">*</span></label>
                                                <input type="text" id="stock" name="stock"
                                                    value="{{ old('stock') }}" class="form-control"
                                                    title="Please enter Current Stock" autocomplete="off" required />
                                                @error('stock')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-4">
                                            <div class="form-group">
                                                <label for="color">Colors</label>
                                                <select id="color" name="colors[]" class="form-control"
                                                    title="Please Select Colors" multiple>
                                                    <option value="0">Select Colors</option>
                                                    @foreach ($colors as $item)
                                                        <option value="{{ $item->id }}">{{ $item->color_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('colors')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-4">
                                            <div class="form-group">
                                                <label>Product SKU <span class="text-danger">*</span></label>
                                                <input type="text" id="product_sku" name="product_sku"
                                                    value="{{ old('product_sku') }}" class="form-control"
                                                    title="Please enter SKU value" autocomplete="off" required
                                                    readonly />
                                                @error('product_sku')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="customSwitch1" name="is_featured" value="1">
                                                    <label class="custom-control-label"
                                                        for="customSwitch1">Featured</label>
                                                </div>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="customSwitch2" name="is_newArrival" value="1">
                                                    <label class="custom-control-label" for="customSwitch2">New
                                                        Arrival</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="customSwitch3" name="is_offers" value="1">
                                                    <label class="custom-control-label"
                                                        for="customSwitch3">Offer</label>
                                                </div>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="customSwitch4" name="is_bestSelling" value="1">
                                                    <label class="custom-control-label" for="customSwitch4">Best
                                                        Selling</label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                                {{-- <div class="tab-pane" id="imagevideo">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12">
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
                                        <div class="col-lg-6 col-md-6 col-xs-6">
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
                                        <div class="col-lg-4 col-md-4 col-xs-4">
                                            <div class="form-group">
                                                <label> Choose Color </label>
                                                <select id="color" name="color" value="{{ old('color') }}"
                                                    class="form-control" title="Please Select Color">
                                                    <option value="">Select Color</option>
                                                    @foreach ($colors as $item)
                                                        <option value="{{ $item->id }}">{{ $item->color_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('color')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div> --}}
                                <div class="tab-pane" id="pricestock">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>
                                                    MRP <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" id="price" name="price"
                                                    value="{{ old('price') }}" class="form-control"
                                                    autocomplete="off" required />
                                                @error('price')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="div6" class="form-group">
                                                <label>
                                                    Customer - Selling Price <span class="text-danger">*</span></label>
                                                <input type="text" control="int" class="form-control"
                                                    autocomplete="off" id="discountprice" name="discountprice"
                                                    value="{{ old('discountprice') }}" required />
                                                @error('discountprice')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="div6" class="form-group">
                                                <label>
                                                    Reseller - Selling Price <span class="text-danger">*</span></label>
                                                <input type="text" control="int" class="form-control"
                                                    autocomplete="off" id="sellerdiscount" name="sellerdiscount"
                                                    value="{{ old('sellerdiscount') }}" required />
                                                @error('sellerdiscount')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <div class="tab-pane" id="productVariant">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Type</label>
                                                <select id="ddlVariantType" name="ddlVariantType"
                                                    class="form-control" title="Please Select Type">
                                                    <option value="">Select Type</option>
                                                    <option value="0">Size</option>
                                                    <option value="1">Other</option>
                                                </select>
                                                @error('ddlVariantType')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2" id="divVariantSize">
                                            <div class="form-group">
                                                <label>
                                                    Size <span class="text-danger">*</span>
                                                </label>
                                                <select id="ddlVariantSize" name="ddlVariantSize"
                                                    class="form-control" title="Please Select Size">
                                                    <option value="">Select Size</option>
                                                    <option value="XS">XS</option>
                                                    <option value="S">S</option>
                                                    <option value="M">M</option>
                                                    <option value="L">L</option>
                                                    <option value="XL">XL</option>
                                                    <option value="XXL">XXL</option>
                                                    <option value="3XL">3XL</option>
                                                    <option value="4XL">4XL</option>
                                                    <option value="5XL">5XL</option>
                                                    <option value="6XL">6XL</option>
                                                </select>
                                                @error('ddlVariantSize')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2" id="divVariantOther">
                                            <div class="form-group">
                                                <label>
                                                    Size as Number <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" control="int" class="form-control"
                                                    autocomplete="off" id="ddlVariantotherSize"
                                                    name="ddlVariantotherSize"
                                                    value="{{ old('ddlVariantotherSize') }}" />
                                                @error('ddlVariantotherSize')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2" id="divVariantStock">
                                            <div class="form-group">
                                                <label>
                                                    Stock <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" id="numVariantStock" name="numVariantStock"
                                                    min="0" value="{{ old('numVariantStock') }}"
                                                    class="form-control" autocomplete="off" />
                                                @error('numVariantStock')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>
                                                    MRP <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" id="numVariantMRPPrice"
                                                    name="numVariantMRPPrice" min="0"
                                                    value="{{ old('numVariantMRPPrice') }}" class="form-control"
                                                    autocomplete="off" />
                                                @error('numVariantMRPPrice')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>
                                                    Customer - Selling Price <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" id="numVariantCSPrice" name="numVariantCSPrice"
                                                    min="0" value="{{ old('numVariantCSPrice') }}"
                                                    class="form-control" autocomplete="off" />
                                                @error('numVariantCSPrice')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>
                                                    Reseller - Selling Price <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" id="numVariantRSPrice" name="numVariantRSPrice"
                                                    min="0" value="{{ old('numVariantRSPrice') }}"
                                                    class="form-control" autocomplete="off" />
                                                @error('numVariantRSPrice')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 mt-3">
                                            <div class="form-group">
                                                <button type="button" id='editVariant' onclick="addVariant();"
                                                    class="btn btn-success mt-3">Add</button>
                                                <button type="button" onclick="formClear();"
                                                    class="btn btn-danger mt-3">Cancel</button>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="pb-3 pt-5">Variant List</h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Size</th>
                                                                    <th>Stock</th>
                                                                    <th>MRP</th>
                                                                    <th>Customer - Selling Price</th>
                                                                    <th>Reseller - Selling Price</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="tbodyVariantList">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="description">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>
                                                    Short Description<span class="text-danger">*</span>
                                                </label>
                                                <input type="text" id="shortdescription" name="shortdescription"
                                                    value="{{ old('shortdescription') }}" class="form-control"
                                                    autocomplete="off">
                                                @error('shortdescription')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div id="div6" class="form-group">
                                                <label>Long Description <span class="text-danger">*</span></label>
                                                <textarea id="longdescription" name="longdescription" value="{{ old('longdescription') }}" class="form-control"
                                                    autocomplete="off"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <div class="tab-pane" id="seo">
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label>
                                                    Meta Title
                                                </label>
                                                <input type="text" id="metaname" name="metaname"
                                                    class="form-control" value="{{ old('metaname') }}"
                                                    autocomplete="off" />
                                            </div>
                                            <div id="div6" class="form-group">
                                                <label>
                                                    Meta Description</label>
                                                <input type="text" control="int" class="form-control"
                                                    value="{{ old('metadescription') }}" autocomplete="off"
                                                    id="metadescription" name="metadescription" maxlength="255" />
                                            </div>

                                            <div id="div19" class="form-group">
                                                <label>
                                                    Meta Keywords</label>
                                                <input type="text" id="metakeywords" name="metakeywords"
                                                    class="form-control" title="Enter Meta Keywords"
                                                    value="{{ old('metakeywords') }}" autocomplete="off"
                                                    maxlength="255" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="div2" class="form-group text-right">
                                                {{-- <button type="submit" id="btnSave" onclick="validation(event);"
                                                    class="btn btn-flat btn-success">Save</button> --}}
                                                <button type="submit" id="btnSave"
                                                    class="btn btn-flat btn-success">Save</button>
                                                <a href="{{ route('product.list') }}"
                                                    class="btn btn-dark btn btn-flat">Goto
                                                    List</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
<link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/select2.min.css') }}">
<script>
    var isSize = {!! json_encode('') !!};
    var isOther = {!! json_encode('') !!};
    var isEdit = {!! json_encode('') !!};
</script>
<script src="{{ asset('backend/assets/js/product/product.js?v=1') }}"></script>
