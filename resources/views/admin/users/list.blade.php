@extends('admin.admin_master')
@section('admin')
@section('title')
Staff
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h5>Staff list</h5>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Master</li>
                <li class="breadcrumb-item active">Staff</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{url('user/create')}}" class="btn btn-dark">Create Staff</a>
                    {{-- <h3 class="card-title">User List</h3> --}}
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Staff Name</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyList">
                                    @php
                                        $serialNo = 1;
                                    @endphp
                                    @foreach ($users as $item )
                                    <tr id="emptyRow" style="height: 25px;">
                                        <td>{{$serialNo}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->email}}</td>              
                                        <td>
                                            <form action="{{ route('user.destroy', $item['id']) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <a href="{{ url('user',$item->id) }}"
                                                class="btn btn-info">Edit</a>
                                            <button class="btn btn-danger btn-sm btn-flat" 
                                            title="Delete Data" onclick="return confirm('Are you sure want to delete this record?')">Delete</button>
                                        </form>
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