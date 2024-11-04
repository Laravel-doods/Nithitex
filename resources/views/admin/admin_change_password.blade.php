@extends('admin.admin_master')
@section('admin')
@section('title')
Admin Change Password
@endsection
<div class="page-content">
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h5>Change Password</h5>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Master</li>
              <li class="breadcrumb-item active">Change Password</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
  </section>
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <form method="post" action="{{ route('update.change.password') }}">
            @csrf
            @if(count($errors) > 0 )
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                      <ul class="p-0 m-0" style="list-style: none;">
                          @foreach($errors->all() as $error)
                          <li>{{$error}}</li>
                          @endforeach
                      </ul>
                  </div>
                  @endif
            <div class="form-group">
              <label>Current Password</label>
              <input type="password" name="oldpassword" id="oldpassword" class="form-control">   
              <span toggle="#oldpassword" class="fa fa-fw fa-eye field-icon toggle-password"></span>   
              @error('oldpassword')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror                          
            </div>

            <div class="form-group">
              <label>New Password</label>
              <input type="password" name="password" id="password" class="form-control">      
              <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>    
              @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror                      
            </div>  

            <div class="form-group">
              <label>Confirm Password</label>
              <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">  
              <span toggle="#password_confirmation" class="fa fa-fw fa-eye field-icon toggle-password"></span>  
              @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror                            
            </div> 

            <div class="text-xs-right">
              <button type="submit" class="btn btn-rounded btn-primary mb-5">Update</button>
            </div>

          </form>
        </div>  
      </div>
    </div>
  </div>
</div>

 
@endsection
