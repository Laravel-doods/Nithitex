@extends('admin.admin_master')
@section('admin')
@section('title')
    Referral History
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Referral History</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">History</li>
                        <li class="breadcrumb-item active">Referral History</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row">
        <div class="col-md-12 mb-4 mb-md-0">
            <div class="card">
                <div class="card-body">
                    <div class="card-datatable table-responsive pt-0">
                        <table id="tblReferralHistory" class="table">
                            <thead class="border-bottom">
                                <tr>
                                    <th>S.No</th>
                                    <th>Customer Name</th>
                                    <th>Referral Code</th>
                                    <th>No Of Referrals</th>
                                    <th>Referral Earned</th>
                                    {{-- <th>Referral Paid</th>
                                    <th>Referral Balance</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateReferralPaymentPopup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel3">Update Referral Payment</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <form action="{{ route('updatereferralpaymet') }}" name="updatereferralpaymet" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-2 mb-lg-0">
                            <div class="card-datatable table-responsive pt-0">
                                <table id="tblReferralpayment" class="table">
                                    <thead class="border-bottom">
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Balance Amount</th>
                                            <th>Amount</th>
                                            <th>Transaction ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hdUserId" id="hdUserId" value="">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('backend/assets/js/referral_history/refferal_history.js') }}"></script>
