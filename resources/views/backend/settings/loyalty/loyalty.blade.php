@extends('admin.admin_master')
@section('admin')
@section('title')
    Loyalty Management
@endsection
<style>
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Loyalty Management</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                        <li class="breadcrumb-item active">Loyalty Management</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row">
        <div class="col-md-12 mb-4 mb-md-0">
            <div class="card">
                <div class="card-body">
                    <form name="referral_settings" action="{{ route('add.loyalty.management') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="id" value="{{ $loyalty->id ?? null }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-name">Loyalty Rate<span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="number" min="0" class="form-control" id="loyaltyRate"
                                        name="loyaltyRate" placeholder="Enter Loyalty Rate"
                                        value="{{ $loyalty->loyalty_rate ?? '' }}" title="Enter Loyalty Rate" required
                                        @if (isset($loyalty) && $loyalty->loyalty_rate !== null) disabled @endif />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-name">Type<span
                                            class="text-danger">*</span>
                                    </label>
                                    <select name="type" id="type">
                                        <option value="" disabled selected>Select Type</option>
                                        <option value="amount" @if (isset($loyalty) && $loyalty->type == 'amount') selected @endif>Amount
                                        </option>
                                        <option value="percent" @if (isset($loyalty) && $loyalty->type == 'percent') selected @endif>
                                            Percent</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-name">Earn Value Per Order<span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="number" min="0" class="form-control" id="earnValue"
                                        name="earnValue" placeholder="Enter Value"
                                        value="{{ $loyalty->earn_per_order ?? null }}" title="Enter Earn value"
                                        required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-name">Max. Points Redeem Per Order<span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="number" min="0" class="form-control" id="pointsRedeemPerOrder"
                                        name="pointsRedeemPerOrder" placeholder="Enter Redeem Points Per Order"
                                        value="{{ $loyalty->max_redeem_per_order ?? null }}"
                                        title="Enter Redeem Points Per Order" required />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mt-4 mb-3 text-right">
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
