@extends('admin.admin_master')
@section('admin')
@section('title')
    Product Color Update
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Product Color</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Master</li>
                        <li class="breadcrumb-item active">Product Color Update</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Product Color</h3>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div id="divColor" class="col-md-4">
                            <form method="post" action="{{ route('color.update', $colors->id) }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $colors->id }}">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">
                                        Product Color Name</label>
                                    <input type="text" id="color" name="color" class="form-control"
                                        title="Please Enter Color Name" value="{{ $colors->color_name }}" required />
                                    @error('Color')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">
                                        Color Code
                                        <span id="color-code-help"
                                            style="display: inline;color: rgb(85, 41, 191);background: rgba(85, 41, 191, 0.28);padding: 4px;border-radius: 12px;font-size: 12px;">Color
                                            code should
                                            start with #</span>
                                    </label>
                                    <input type="text" id="code" name="code" class="form-control"
                                        title="Please Enter Color Code" value="{{ $colors->color_code }}"
                                        placeholder="Enter Color Code" required />
                                    @error('Color')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="btnSave" class="btn btn-success">Save</button>
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
