@extends('admin.admin_master')
@section('admin')
@section('title')
    All Resellers
@endsection
@php
    $coupon = App\Models\Coupon::where('is_common', 0)
        ->orderBy('id', 'ASC')
        ->get();
@endphp
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>All Resellers </h5>
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
                            <table id="dataTableExample" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Bank Name</th>
                                        <th>Account Holder Name</th>
                                        <th>Bank Account Number</th>
                                        <th>Bank IFSC</th>
                                        <th>Applied Coupon</th>
                                        <th>Apply Coupon</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->phone }}</td>
                                            <td>{{ $item->bank_name }}</td>
                                            <td>{{ $item->bank_account_name }}</td>
                                            <td>{{ $item->bank_account_number }}</td>
                                            <td>{{ $item->bank_ifsc }}</td>
                                            <td>
                                                @if ($item->coupon_name)
                                                    {{ $item->coupon_name }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary" id="showCouponpopup"
                                                    title="Apply Coupon"
                                                    onclick="showCouponpopup({{ $item->id }});">Apply
                                                    Coupon</button>
                                            </td>
                                        </tr>
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

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Apply
                    Coupon</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('sellerCouponUpdate') }}" method="post">
                            @csrf
                            <label for="">Select Coupon Name To
                                Apply</label>
                            <select id="ddlCouponType" name="ddlCouponType" class="form-control"
                                title="Please Select Coupon Type" required>
                                <option value="">Select Coupon
                                </option>
                                @foreach ($coupon as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $item->coupon_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->coupon_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="modal-footer">
                                <input type="hidden" name="hidUserId" id="hidUserId" value="">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    function showCouponpopup(id) {
        $('#hidUserId').val(id);
        $("#staticBackdrop").on('shown.bs.modal', function() {}).on('hidden.bs.modal', function() {}).modal({
            backdrop: 'static',
            keyboard: true,
            show: true
        }, 'show');
    }
</script>
