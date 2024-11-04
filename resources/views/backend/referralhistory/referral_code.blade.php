@extends('admin.admin_master')
@section('admin')
@section('title')
    Referral Code
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>{{ $referral_name->name }} referrals</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">History</li>
                        <li class="breadcrumb-item active">Referrals</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
        <div class="row">
            <div class="col-md-12 text-right mb-3 pt-2">
                <a href="{{ route('referral-history') }}"><button type="button"
                    class="btn btn-primary">Back</button></a>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-md-12 mb-4 mb-md-0">
            <div class="card">
                <div class="card-body">
                    <div class="col-lg-12 mb-4 mb-lg-0">
                        <div class="card h-100">
                            <div class="card-datatable table-responsive pt-0">
                                <table id="tblRefferalCode" class="table">
                                    <thead class="border-bottom">
                                        <tr>
                                            <th>S.No</th>
                                            <th>Used By</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($username as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->referred_on }}</td>
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
</div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('backend/assets/js/referral_histoy/referral_histoy.js') }}"></script>
