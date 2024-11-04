@extends('admin.admin_master')
@section('admin')
@section('title')
    Customer Cancel Request List
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Customer Cancel Request List </h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Master</li>
                        <li class="breadcrumb-item active">Customer Cancel Request List</li>
                    </ol>
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
                                        <th>Order No</th>
                                        <th>Customer</th>
                                        <th>Total Price</th>
                                        <th>Payment Status</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyList">
                                    @php
                                        $serialNo = 1;
                                    @endphp
                                    @foreach ($orders as $item)
                                        <tr id="emptyRow" style="height: 25px;">
                                            <td>{{ $serialNo }}</td>
                                            <td>{{ $item->order_number }}</td>
                                            <td>{{ $item->user->name }}</td>
                                            <td>{{ $item->amount }}</td>
                                            <td><label for="">
                                                    @if ($item->payment_status == 'paid')
                                                        <span
                                                            class="badge badge-pill badge-success text-white">{{ $item->payment_status }}
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge badge-pill badge-danger text-white">{{ $item->payment_status }}
                                                        </span>
                                                    @endif
                                                </label></td>
                                            <td>{{ $item->payment_type }}</td>
                                            <td>
                                                @if ($item->cancel_request == 1)
                                                    <span class="badge badge-pill badge-primary">Pending </span>
                                                @elseif($item->Cancel == 2)
                                                    <span class="badge badge-pill badge-success">Success </span>
                                                @endif

                                            </td>
                                            <td>
                                                <a href="{{ route('cancel.approve', $item->id) }}"
                                                    class="btn btn-danger">Approve </a>
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
@endsection
