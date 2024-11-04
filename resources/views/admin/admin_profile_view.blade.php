@extends('admin.admin_master')
@section('admin')
@section('title')
Admin Profile Update
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h5>Update Profile </h5>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Master</li>
                <li class="breadcrumb-item active">Update Profile</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                  <form method="post" action="{{ route('admin.profile.store') }}" enctype="multipart/form-data" >
                    @csrf
                    <input type="hidden" name="img" value="{{$adminData->profile_photo_path}}">
                  <div class="form-group">
                    <label class="info-title" for="exampleInputEmail1">Name <span> </span></label>
                    <input type="name" name="name" class="form-control" value="{{$adminData->name}}">                                
                </div>
        
                <div class="form-group">
                    <label class="info-title" for="exampleInputEmail1">Email <span> </span></label>
                    <input type="text" name="email" class="form-control" value="{{$adminData->email}}">                                
                </div>    
        
                <div class="form-group">
                    <label class="info-title" for="exampleInputEmail1">User Image <span> </span></label>
                    <input type="file" id="image" name="profile_photo_path" class="form-control" required="" onChange="mainThamUrl(this)"><br>
               <img src="@if($adminData->profile_photo_path == null){{asset('profile.png')}} @else {{asset('upload/admin_images/'.$adminData->profile_photo_path)}} @endif" id="showImage" style="width: 120px; height:100px;">   

               </div>
                {{-- </div>     --}}
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
<script type="text/javascript">
    function mainThamUrl(input){
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e){
              $('#showImage').attr('src',e.target.result).width(80).height(80);
          };
          reader.readAsDataURL(input.files[0]);
      }
  }
</script>