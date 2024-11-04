@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="container-full">
    <section class="content">

        <!-- Basic Forms -->
         <div class="box">
           <div class="box-header with-border">
             <h4 class="box-title">Admin Profile  Edit</h4>
           </div>
           <!-- /.box-header -->
           <div class="box-body">
             <div class="row">
               <div class="col">
                <form method="post" action="{{ route('admin.profile.store') }}" enctype="multipart/form-data" >
                    @csrf
                    <input type="hidden" name="img" value="{{$editData->profile_photo_path}}">
                     <div class="row">
                       <div class="col-12">		
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h5>Admin Name <span class="text-danger">*</span></h5>
                                    <div class="controls">
                                        <input type="text" name="name" id="name" value="{{$editData->name}}" class="form-control" required="" data-validation-required-message="This field is required"></div>
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h5>Email Field <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="email" name="email" value="{{$editData->email}}" class="form-control" required="" data-validation-required-message="This field is required">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h5>File Input Field <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="file" id="image" name="profile_photo_path" class="form-control" onChange="mainThamUrl(this)" required=""> </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                        <img src="@if($editData->profile_photo_path == null){{asset('upload\admin_images\1741573539360391.jpg')}} @else {{asset('upload/admin_images/'.$editData->profile_photo_path)}}" id="showImage" style="width: 100px; height:100px;">
                                    </div>
                                </div>				                    
                       <div class="text-xs-right">
                           <button type="submit" class="btn btn-rounded btn-primary mb-5">Update</button>
                       </div>
                   </form>

               </div>
               <!-- /.col -->
             </div>
             <!-- /.row -->
           </div>
           <!-- /.box-body -->
         </div>
         <!-- /.box -->

       </section>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#image').change(function(e){
                var reader = new FileReader();
                reader.onload = function(e){
                 $('#showImage').attr('src',e.target.result);	
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>

@endsection
