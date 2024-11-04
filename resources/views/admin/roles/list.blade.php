@extends('admin.admin_master')
@section('admin')
@section('title')
Role
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h5>Users Role list</h5>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Master</li>
                <li class="breadcrumb-item active">Role</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{url('role/create')}}" class="btn btn-dark">Create Role</a>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Role Name</th>
                                        {{-- <th>Permissions</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyList">
                                    @php
                                        $serialNo = 1;
                                    @endphp
                                    @foreach ($roles as $item )
                                    <tr id="emptyRow" style="height: 25px;">
                                        <td>{{$serialNo}}</td>
                                        <td>{{$item->name}}</td>
                                        {{-- <td>{{implode(",",$item->permissions->pluck('name')->toArray())}}</td> --}}
                                        <td>
                                            @if($item->id != 1)
                                            <a href="{{url('role')}}/{{$item->id}}" class ="btn btn-info btn-sm btn-flat">Edit</a>
                                            @endif
                                            {{-- <form action="{{url('role')}}/{{$item->id}}" method="POST">                                            
                                                @csrf
                                                @method('DELETE')
                                                <button type="Submit" class="btn btn-danger btn-sm btn-flat" onclick="return confirm('Are you sure want to delete this record?')">Delete</button>
                                            </form> --}}
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