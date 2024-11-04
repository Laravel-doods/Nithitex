@extends('admin.admin_master')
@section('admin')
@section('title')
    Product Category Update
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Product Main Category</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Master</li>
                        <li class="breadcrumb-item active">Product Main Category Update</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Product Main Category</h3>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div id="divCategory" class="col-md-4">
                            <form method="post" action="{{ route('main-category.update', $main_categories->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $main_categories->id }}">
                                <input type="hidden" name="old_image" value="{{ $main_categories->main_category_image }}">
                                <div class="form-group">
                                    <label for="main_category_name">
                                        Main Category Name<span class="text-danger">*</span></label>
                                    <input type="text" id="main_category_name" name="main_category_name" class="form-control"
                                        title="Please Enter Category Name" value="{{ $main_categories->main_category_name }}"
                                        required />
                                    @error('MainCategory')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Main Category Image<span class="text-danger">*</span></label>
                                    <input type="file" id="main_category_image" name="main_category_image" class="form-control"
                                        onChange="mainThamUrl(this)" />
                                    @error('main_category_image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror <br>
                                    <img src="{{ asset($main_categories->main_category_image) }}" height="100" width="100"
                                        id="mainThmb">
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="btnSave" class="btn btn-primary">Update</button>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script type="text/javascript">
    function mainThamUrl(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#mainThmb').attr('src', e.target.result).width(80).height(80);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
