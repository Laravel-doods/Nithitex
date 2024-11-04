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
              <h5>User Roles</h5>
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
                <div class="card-body">
                    {{-- <div class="row"> --}}
                         <form  action = "{{url('role')}}/{{$role->id}}"  method ="POST" >
                            @csrf
                            @method('PUT')
                        <div class="mb-3">
                          <label for="exampleInputName" class="form-label">Name</label>
                            <input name ="name" value ="{{$role->name}}"type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        </div>
                       
                        @if($permissionGroups->count())
                        <ul>
                            <div class="row">
                             @foreach($permissionGroups as $permissionGroup)
                                 <div class ="col-md-4">
                                    <div class="form-check">
                                      <h4>{{$permissionGroup->name}}</h4>
                                   
                                     @if($permissionGroup->permissions->count())
                                        @foreach($permissionGroup->permissions as $permission)
                                           <input 
                                           @if(in_array ($permission->id,$role->permissions->pluck('id')->toArray()))
                                                checked
                                           @endif 
                                           value="{{$permission->id}}" name = "permission_ids[]" class="form-check-input" type="checkbox" value="" >
                                              <label class="form-check-label" for="flexCheckDefault">
                                                {{$permission->name}}
                                                  </label><br>
                                         @endforeach
                                      @endif 
                                     </div>
                                  </div>
                             @endforeach
                          </div>
                        @endif
                        
                           <button type="submit" class="btn btn-primary">Update Role</button>
                              </form>
                       
                        
                    {{-- </div> --}}
                </div>  
            </div>
        </div>
    </div>
</div>

 
@endsection