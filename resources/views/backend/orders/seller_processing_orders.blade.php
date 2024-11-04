@extends('admin.admin_master')
@section('admin')
@section('title')
Reseller Processing Order List 
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h5>Reseller Processing Order List </h5>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Master</li>
                <li class="breadcrumb-item active">Reseller Processing Order List</li>
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
                                        <th>Reseller Name</th>
                                        <th>Order To</th>
                                        <th>Qty</th>
                                        <th>Sub Total</th>
                                        <th>Discount</th>
                                        <th>Shipping</th>
                                        <th>Margin</th>
                                        <th>Net Amount</th>
                                        <th>Delivery Status</th>
                                        <th>Payment Status</th>
                                        <th>Payment Method</th>
                                        <th>Shipping Label</th>
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
                                            <div class="text-muted bg-light p-1"><small>{{$item->user->phone}}</small></div>
                                            @if ($item->user->city_name || $item->user->state_name)
                                                <div class="text-muted bg-light p-1 mt-1"><small>{{$item->user->city_name}} @if($item->user->state_name), {{$item->user->state_name}}@endif</small></div>
                                            @endif
                                        </td>
                                        <td>{{$item->name}}
                                            <div class="text-muted bg-light p-1"><small>{{$item->phone}}</small>
                                                @if($item->alternative_number)
                                                    / <small>{{$item->alternative_number}}</small>
                                                @endif
                                            </div>
                                            <div class="text-muted bg-light p-1 mt-1"><small>{{$item->city_name}} , {{$item->state_name}}</small></div>
                                        </td>
                                        <td>{{$item->tot_Qty}}</td>
                                        <td>{{round($item->sub_total)}}</td>
                                        <td>{{round($item->coupon_discount)}}</td>
                                        <td>{{round($item->shipping_charge)}}</td>
                                        <td>@if($item->margin_amount){{round($item->margin_amount)}} @else 0 @endif</td>
                                        <td>{{round($item->amount)}}</td>
                                        <td>
                                        <label for="">                              
                                                <span class="badge badge-pill badge-warning text-white" style="background: #800080;"> Processing </span>
                                        </label>
                                    </td>
                                        <td>
                                            <label for="">
                                            @if($item->payment_status == 'paid')
                                            <a class="badge badge-pill badge-success text-white" href="{{ route('seller.order.unpaid_status.update',$item->id) }}"> Paid </a>
                                            @else
                                            <a class="badge badge-pill badge-danger text-white" href="{{ route('seller.order.paid_status.update',$item->id) }}"> UnPaid </a>
                                            @endif
                                            </label>                   
                                        </td>
                                        <td>{{$item->payment_type}}</td>
                                        
                                        <td><a class="btn btn-success" href="{{ route('order.print.modal',$item->id) }}"  class="btn btn-success" target="_blank">Print</a></td>
                                        
                                        <form action="{{route('seller.processing.order.approve',$item->id)}}" method="POST">
                                            @csrf
                                        <td>
                                            <button  type="submit" title="Ready To Pick" class="btn btn-info"><i class="fa fa-eye"></i>Ready To Pick</button>
                                        </td>
                                        </form>
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