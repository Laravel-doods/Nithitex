@extends('admin.admin_master')
@section('admin')
@section('title')
    Coupon
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Generate Coupon</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                        <li class="breadcrumb-item active">Coupon</li>
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
                        <div id="divCategory" class="col-lg-4 col-md-4">
                            <form method="post" action="{{ route('coupon.update') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $coupon->id }}">
                                <div class="form-group">
                                    <label>Coupon Name<span class="text-danger"> *</span></label>
                                    <input type="text" name="coupon_name" placeholder="Enter Coupon Name"
                                        class="form-control" autocomplete="off" value="{{ $coupon->coupon_name }}"
                                        required>
                                    @error('coupon_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Start Date<span class="text-danger"> *</span></label>
                                    <input type="date" name="start_date" class="form-control" autocomplete="off"
                                        value="{{ $coupon->start_date }}" required>
                                    @error('start_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>End Date<span class="text-danger"> *</span></label>
                                    <input type="date" name="end_date" class="form-control" autocomplete="off"
                                        value="{{ $coupon->end_date }}" required>
                                    @error('end_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Coupon Code<span class="text-danger"> *</span></label>
                                    <input type="text" name="coupon_code" placeholder="Enter Coupon Code"
                                        class="form-control" autocomplete="off" value="{{ $coupon->coupon_code }}"
                                        required>
                                    @error('coupon_code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Color Code<span class="text-danger"> *</span></label>
                                    <input type="text" name="color_code" placeholder="Enter Color Code"
                                        class="form-control" autocomplete="off" required
                                        value="{{ $coupon->color_code}}">
                                    @error('color_code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Discount Percentage<span class="text-danger"> *</span></label>
                                    <div class="input-group">
                                        <input type="text" name="discount_percentage"
                                            placeholder="Enter Discount Percentage" class="form-control"
                                            autocomplete="off" value="{{ $coupon->discount_percentage }}" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text text-dark">%</span>
                                        </div>
                                    </div>
                                    @error('discount_percentage')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch1"
                                            name="common" value="{{ $coupon->is_common }}"
                                            @if ($coupon->is_common == 1) checked @endif>
                                        <label class="custom-control-label" for="customSwitch1">Common</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" id="btnSave" class="btn btn-success">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
