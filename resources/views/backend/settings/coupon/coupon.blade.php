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
                            <form method="post" action="{{ route('coupon.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Coupon Name<span class="text-danger"> *</span></label>
                                    <input type="text" name="coupon_name" placeholder="Enter Coupon Name"
                                        class="form-control" autocomplete="off" required
                                        value="{{ old('coupon_name') }}">
                                    @error('coupon_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Start Date<span class="text-danger"> *</span></label>
                                    <input type="date" name="start_date" class="form-control" autocomplete="off"
                                        required value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>End Date<span class="text-danger"> *</span></label>
                                    <input type="date" name="end_date" class="form-control" autocomplete="off"
                                        required value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Coupon Code<span class="text-danger"> *</span></label>
                                    <input type="text" name="coupon_code" placeholder="Enter Coupon Code"
                                        class="form-control" autocomplete="off" required
                                        value="{{ old('coupon_code') }}">
                                    @error('coupon_code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Color Code<span class="text-danger"> *</span></label>
                                    <input type="text" name="color_code" placeholder="Enter Color Code"
                                        class="form-control" autocomplete="off" required
                                        value="{{ old('color_code') }}">
                                    @error('color_code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Discount Percentage <span class="text-danger"> *</span></label>
                                    <div class="input-group">
                                        <input type="text" name="discount_percentage"
                                            placeholder="Enter Discount Percentage" class="form-control"
                                            autocomplete="off" required value="{{ old('discount_percentage') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text text-dark">%</span>
                                        </div>
                                    </div>
                                </div>
                                @error('discount_percentage')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch1"
                                            name="common" value="1">
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
            <h5>Coupon List</h5>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="dataTableExample" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Coupon Name</th>
                                                <th>Coupon Code</th>
                                                <th>Color Code</th>
                                                <th>Discount Percentage</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Common</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyList">
                                            @php
                                                $serialNo = 1;
                                            @endphp
                                            @foreach ($coupon as $item)
                                                <tr>
                                                    <td>{{ $serialNo }}</td>
                                                    <td>{{ $item->coupon_name }}</td>
                                                    <td>{{ $item->coupon_code }}</td>
                                                    <td>{{ $item->color_code }}</td>
                                                    <td>{{ $item->discount_percentage }}%</td>
                                                    <td>{{ Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }}
                                                    </td>
                                                    <td>{{ Carbon\Carbon::parse($item->end_date)->format('d-m-Y') }}
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-switch">
                                                            <form action="{{ route('coupon.common') }}" method="post"
                                                                id="coupon_common{{ $item->id }}">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $item->id }}">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="common{{ $item->id }}" name="common"
                                                                    value="1"
                                                                    @if ($item->is_common == 1) checked @endif
                                                                    onclick="commoncouponupdate({{ $item->id }});">
                                                                <label class="custom-control-label"
                                                                    for="common{{ $item->id }}"></label>
                                                            </form>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-switch">
                                                            <form action="{{ route('coupon.status') }}"
                                                                method="post" id="coupon_submit{{ $item->id }}">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $item->id }}">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="active{{ $item->id }}" name="active"
                                                                    value="1"
                                                                    @if ($item->is_active == 1) checked @endif
                                                                    onclick="docouponupdate({{ $item->id }});">
                                                                <label class="custom-control-label"
                                                                    for="active{{ $item->id }}"></label>
                                                            </form>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('coupon.edit', $item->id) }}"
                                                            class="btn btn-info btn-sm btn-flat"
                                                            title="Edit Data">Edit
                                                        </a>
                                                    </td>
                                                </tr>
                                                @php
                                                    $serialNo++;
                                                @endphp
                                            @endforeach
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
</div>
@endsection
