@extends('admin.admin_master')
@section('admin')
@section('title')
Shipped Order List 
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h5>Shipped Order List </h5>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Master</li>
                <li class="breadcrumb-item active">Shipped Order List</li>
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
                                        <th>Order Date</th>
                                        <th>Order No</th>
                                        <th>Customer Name</th>
                                        <th>Qty</th>
                                        <th>Sub Total</th>
                                        <th>Discount</th>
                                        <th>Shipping</th>
                                        <th>Net Amount</th>
                                        <th>Delivery Status</th>
                                        <th>Payment Status</th>
                                        <th>Payment Method</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyList">
                                    @php
                                        $serialNo = 1;
                                    @endphp
                                    @foreach($orders as $item)
                                    <tr id="emptyRow" style="height: 25px;">
                                        <td>{{$serialNo}}</td>
                                        <td>{{($item->created_at)->format('d/m/Y')}}</td>
                                        <td>{{$item->order_number}}</td>
                                        <td>{{$item->user->name}}
                                            <div class="text-muted bg-light p-1"><small>{{$item->phone}}</small></div>
                                            <div class="text-muted bg-light p-1 mt-1"><small>{{$item->city_name}} , {{$item->state_name}}</small></div>
                                        </td>
                                        <td>{{$item->tot_Qty}}</td>
                                        <td>{{round($item->sub_total)}}</td>
                                        <td>{{round($item->coupon_discount)}}</td>
                                        <td>{{round($item->shipping_charge)}}</td>
                                        <td>{{round($item->amount)}}</td>
                                        <td>
                                        <label for="">                              
                                                <span class="badge badge-pill badge-warning text-white" style="background: #808080;"> Shipped </span>
                                        </label>
                                        @if($item->track_number)   
                                        <div class="text-muted bg-light p-1"><small>{{$item->track_number}}</small></div>
                                        @endif
                                    </td>
                                        <td>
                                            <label for="">
                                            @if($item->payment_status == 'paid')
                                            <a class="badge badge-pill badge-success text-white" href=""> Paid </a>
                                            @else
                                            <a class="badge badge-pill badge-danger text-white" href="{{ route('order.paid_status.update',$item->id) }}"> UnPaid </a>
                                            @endif
                                            </label>                   
                                        </td>
                                        <td>{{$item->payment_type}}</td>
                                        <td>
                                            <a href="{{ route('shipped.order.approve',$item->id) }}" class="btn btn-info" title="Edit Data"><i class="fa fa-eye"></i>Move To Delivered</a>
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