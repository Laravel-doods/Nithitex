@extends('admin.admin_master')
@section('admin')
@section('title')
Edit Assign User Role 
@endsection

<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Edit Assign User Role</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">Master</li>
                        <li class="breadcrumb-item active">Edit Assign User Role</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{url('assign_role_users')}}" class="btn btn-dark">User Role List</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div id="divTax" class="col-md-4">                                         
                            <form action = "{{url('assign_role_users',$selecteduser->id)}}"  method ="POST">
                                @csrf
                                @method('PUT')
                            <div class="form-group">
                                <label>Staff</label>
                                <select disabled name="user_id" class="form-control">
                                <option value="">Select Staff</option>
                                @if($users->count()>0)
                                    @foreach($users as $user)
                                    <option 
                                    @if($user->id == $selecteduser->id) selected
                                    @endif value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Role</label>
                                <select  name="role_id" class="form-control">
                                    <option value="">Select Role</option>
                                    @if($roles->count()>0)
                                        @foreach($roles as $role)
                                        <option 
                                        @if(in_array($role->id, $selecteduser->roles->pluck('id')->toArray())) selected
                                        @endif
                                        value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="btnSave" class="btn btn-success">Save</button>
                    </div>
                </div>
                </form>
            </div>  
        </div>
    </div>
</div> 
@endsection