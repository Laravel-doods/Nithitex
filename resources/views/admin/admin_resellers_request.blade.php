@extends('admin.admin_master')
@section('admin')
@section('title')
Resellers Requests
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h5>Resellers Requests</h5>
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
                                        <th>Reseller Name</th>
                                        <th>Shop Name</th>
                                        <th>Account Holder Name</th>
                                        <th>Account Number</th>
                                        <th>IFSC Code</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Bank Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            {{$item->name}}                                
                                            
                                        </td>
                                        <td>{{$item->shop_name}}</td>
                                        <td>{{$item->bank_account_name}}</td>
                                        <td>{{$item->bank_account_number}}</td>
                                        <td>{{$item->bank_ifsc}}</td>
                                        <td>{{$item->email}}</td>
                                        <td>{{$item->phone}}</td>
                                        <td>{{$item->bank_name}}</td>
                                        <td>
                                            <a href="{{route('reseller.approve',$item->id)}}" class="btn btn-success" title="Approve">Approve</a>
                                            <a href="{{route('reseller.deny',$item->id)}}" class="btn btn-danger" title="Deny">Deny</a>
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
@endsection

            
            


