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
                    <h4>Edit Product</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Master</li>
                        <li class="breadcrumb-item active">Edit Product</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row">
        <div class="col-sm-12">
            <form method="post" action="{{ route('product.update') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $products->id }}">
                <input type="hidden" name="old_img" value="{{ $products->product_image }}">
                <input type="hidden" id="hdProductVariant" name="hdProductVariant" value="0">
                <input type="hidden" id="hdVariantType" name="hdVariantType" value="{{ $variant_type }}">

                <div class="card">
                    <div class="nav-tabs-custom card-box" id="divLed">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#information" data-toggle="tab">Product Information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#imagevideo" data-toggle="tab">Images & Videos</a>
                            </li>
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
                                        <div class="col-lg-3 col-md-3 col-xs-3">
                                            <div class="form-group">
                                                <label for="txtDesignationName">
                                                    Product Name<span class="text-danger">*</span></label>
                                                <input type="text" id="product_name"
                                                    title="Please Enter Product Name" name="product_name"
                                                    placeholder="Enter Product Name"
                                                    value="{{ $products->product_name }}" class="form-control"
                                                    autocomplete="off" required />
                                                @error('product_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-3">
                                            <div class="form-group">
                                                <label for="ddlMainCategoryType">Main Category Type <span
                                                        class="text-danger">*</span></label>
                                                <select id="ddlMainCategoryType" name="ddlMainCategoryType"
                                                    class="form-control" title="Please Select Main Category Type"
                                                    required>
                                                    <option value="">Select Main Category</option>
                                                    @foreach ($main_category as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $item->id == $products->main_category_id ? 'selected' : '' }}>
                                                            {{ $item->main_category_name }}</option>
                                                    @endforeach
                                                   
                                                </select>
                                                @error('ddlMainCategoryType')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-3">
                                            <div class="form-group">
                                                <label for="txtDepartmentName">
                                                    Category Type<span class="text-danger">*</span></label>
                                                <select id="ddlCategoryTypeUp" name="ddlCategoryTypeUp"
                                                    class="form-control" title="Please Select Category Type" required>
                                                    <option value="">Select Category</option>
                                                    @foreach ($categories as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $item->id == $products->category_id ? 'selected' : '' }}>
                                                            {{ $item->category_name }}</option>
                                                    @endforeach
                                                    
                                                </select>
                                                @error('ddlCategoryTypeUp')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        @php
                                            use App\Models\Product;
                                            use App\Models\Colors;

                                            $color_ids = Product::where('group_id', $products->group_id)->pluck(
                                                'color_id',
                                            );
                                            $filtered_color_ids = $color_ids->diff([$products->color_id]);
                                            $colors = Colors::whereNotIn('id', $filtered_color_ids)->get();
                                        @endphp
                                        <div class="col-lg-3 col-md-3 col-xs-3">
                                            <div class="form-group">
                                                <label> Color </label>
                                                <select id="color" name="color" class="form-control">
                                                    <option value="">Select Color</option>
                                                    @foreach ($colors as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $item->id == $products->color_id ? 'selected' : '' }}>
                                                            {{ $item->color_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('color')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-3">
                                            <div class="form-group">
                                                <label>Tag</label>
                                                <input type="text" id="tags" name="tags"
                                                    value="{{ $products->tags }}" data-role="tagsinput"
                                                    class="form-control" width="100%">
                                                @error('tags')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-xs-3">
                                            <div class="form-group">
                                                <label>Current Stock<span class="text-danger">*</span></label>
                                                <input type="text" id="stock" name="stock"
                                                    class="form-control" title="Please enter Current Stock"
                                                    autocomplete="off" value="{{ $products->current_stock }}"
                                                    required />
                                                @error('stock')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-3">
                                            <div class="form-group">
                                                <label>Product SKU<span class="text-danger">*</span></label>
                                                <input type="text" id="product_sku" name="product_sku"
                                                    class="form-control" title="Please enter SKU value"
                                                    autocomplete="off" value="{{ $products->product_sku }}" required
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
                                                        id="customSwitch1" name="is_featured" value="1"
                                                        {{ $products->is_featured == 1 ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="customSwitch1">Featured</label>
                                                </div>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="customSwitch2" name="is_newArrival" value="1"
                                                        {{ $products->is_newArrival == 1 ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="customSwitch2">New
                                                        Arrival</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="customSwitch3" name="is_offers" value="1"
                                                        {{ $products->is_offers == 1 ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="customSwitch3">Offer</label>
                                                </div>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="customSwitch4" name="is_bestSelling" value="1"
                                                        {{ $products->is_bestSelling == 1 ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="customSwitch4">Best
                                                        Selling</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="imagevideo">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>
                                                    Product Images<span class="text-danger">*</span>
                                                    <div class="text-muted"><small>(Multiple images allowed)</small>
                                                    </div>
                                                </label>
                                                <div class="upload__box">
                                                    <div class="upload__btn-box">
                                                        <label class="upload__btn">
                                                            <p>Upload</p>
                                                            <input type="file" id="multiImg" name="multi_img[]"
                                                                multiple="" data-max_length="20"
                                                                class="form-control upload__inputfile">
                                                        </label>
                                                        @error('multi_img')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="upload__img-wrap">
                                                        @foreach ($multiImgs as $img)
                                                            <div class='upload__img-box'>
                                                                <div style='background-image: url({{ asset($img->product_mult_image) }})'
                                                                    class='img-bg'>
                                                                    <div class='upload__img-close'
                                                                        id={{ $img->id }}
                                                                        onclick="deleteimg({{ $img->id }});">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>
                                                    Product Video Link
                                                </label>
                                                <input type="text" id="video_link"
                                                    value="{{ $products->product_video_url }}" name="video_link"
                                                    class="form-control">
                                                @error('video_link')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <div class="tab-pane" id="pricestock">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>
                                                    MRP<span class="text-danger">*</span>
                                                </label>
                                                <input type="text" id="price" name="price"
                                                    class="form-control" autocomplete="off"
                                                    value="{{ $products->product_price }}" required />
                                                @error('price')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="div6" class="form-group">
                                                <label>
                                                    Customer - Selling Price<span class="text-danger">*</span></label>
                                                <input type="text" control="int" class="form-control"
                                                    autocomplete="off" id="discountprice" name="discountprice"
                                                    value="{{ $products->product_discount }}" required />
                                                @error('discountprice')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="div6" class="form-group">
                                                <label>
                                                    Reseller - Selling Price<span class="text-danger">*</span></label>
                                                <input type="text" control="int" class="form-control"
                                                    autocomplete="off" id="sellerdiscount" name="sellerdiscount"
                                                    value="{{ $products->seller_discount }}" required />
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
                                                                @if ($product_variants != null)
                                                                    @foreach ($product_variants as $key => $variant)
                                                                        <tr id="trProductVariant{{ $key + 1 }}"
                                                                            typ="{{ (float) $variant->size }}"
                                                                            {{-- typ="{{ $variant->size ?? '--' == null ? $variant->meter : $variant->size }}" --}}
                                                                            VSZ="{{ $variant->size }}"
                                                                            VSZT="{{ $variant->size }}"
                                                                            VSTK="{{ $variant->stock }}"
                                                                            VPRC="{{ $variant->price }}"
                                                                            VCSPrice="{{ $variant->customer_price }}"
                                                                            VRSPrice="{{ $variant->seller_price }}">
                                                                            <td><input type='hidden'
                                                                                    class='variantClass'
                                                                                    id="tabVariantSize{{ $key + 1 }}"
                                                                                    name='tabVariantSize[]'
                                                                                    value="{{ $variant->size }}"><span
                                                                                    id='spnVariantSize'>{{ $variant->size ?? '--' }}</span>
                                                                            </td>
                                                                            <td><input type='hidden'
                                                                                    class='variantClass'
                                                                                    id="tabVariantStock{{ $key + 1 }}"
                                                                                    name='tabVariantStock[]'
                                                                                    value="{{ $variant->stock }}"><span
                                                                                    id='spnVariantStock'>{{ $variant->stock == 0 || $variant->stock < 0 ? '--' : $variant->stock }}</span>
                                                                            </td>
                                                                            <td><input type='hidden'
                                                                                    class='variantClass'
                                                                                    id="tabVariantMRPPrice{{ $key + 1 }}"
                                                                                    name='tabVariantMRPPrice[]'
                                                                                    value="{{ $variant->price }}"><span
                                                                                    id='spnVariantMRPPrice'>{{ $variant->price }}</span>
                                                                            </td>
                                                                            <td><input type='hidden'
                                                                                    class='variantClass'
                                                                                    id="tabVariantCSPrice{{ $key + 1 }}"
                                                                                    name='tabVariantCSPrice[]'
                                                                                    value="{{ $variant->customer_price }}"><span
                                                                                    id='spnVariantCSPrice'>{{ $variant->customer_price }}</span>
                                                                            </td>
                                                                            <td><input type='hidden'
                                                                                    class='variantClass'
                                                                                    id="tabVariantRSPrice{{ $key + 1 }}"
                                                                                    name='tabVariantRSPrice[]'
                                                                                    value="{{ $variant->seller_price }}"><span
                                                                                    id='spnVariantRSPrice'>{{ $variant->seller_price }}</span>
                                                                            </td>
                                                                            <td><a><button type="button"
                                                                                        id="btnSave"
                                                                                        onclick='doEdit({{ $key + 1 }});'
                                                                                        class="btn btn-xs btn-flat btn-success">Edit</button></a><a><button
                                                                                        type="button" id="btnSave"
                                                                                        onclick='removeRow({{ $key + 1 }});'
                                                                                        class="btn btn-xs btn-flat btn-danger">Delete</button></a>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
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
                                                <input id="shortdescription" name="shortdescription"
                                                    class="form-control" autocomplete="off"
                                                    value="{{ $products->short_description }}">
                                                @error('shortdescription')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div id="div6" class="form-group">
                                                <label>
                                                    Long Description<span class="text-danger">*</span></label>
                                                <textarea id="longdescription" name="longdescription" rows="10" class="form-control" autocomplete="off">{{ $products->long_description }}</textarea>
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
                                                    class="form-control" autocomplete="off"
                                                    value="{{ $products->meta_title }}" />
                                            </div>
                                            <div id="div6" class="form-group">
                                                <label>
                                                    Meta Description</label>
                                                <input type="text" control="int" class="form-control"
                                                    autocomplete="off" id="metadescription" name="metadescription"
                                                    value="{{ $products->meta_description }}" />
                                            </div>

                                            <div id="div19" class="form-group">
                                                <label>
                                                    Meta Keywords</label>
                                                <input type="text" id="metakeywords" name="metakeywords"
                                                    value="{{ $products->meta_keywords }}" class="form-control"
                                                    title="Enter Meta Keywords" autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="div2" class="form-group text-right">
                                            <button type="submit" id="btnSave"
                                                class="btn btn-flat btn-success">Save</button>
                                            <a href="{{ route('product.list') }}"
                                                class="btn btn-dark btn btn-flat">Goto
                                                List</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    var isSize = {!! json_encode($isSize) !!};
    var isOther = {!! json_encode($isOther) !!};
    var isEdit = {!! json_encode($isEdit) !!};
</script>
<script src="{{ asset('backend/assets/js/product/product.js?v=3.1') }}"></script>
@endsection

