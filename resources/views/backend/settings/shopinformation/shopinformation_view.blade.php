@extends('admin.admin_master')
@section('admin')
@section('title')
Shop Information
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Shop Information</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                        <li class="breadcrumb-item active">Shop Information</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">   
                        <div class="col-lg-6 col-md-6">
                            <form method="post" action="{{ route('information.update') }}" enctype="multipart/form-data">
                                @csrf
                                    <input type="hidden" name="id" value="{{ $shopInformation->id }}">
                                    <input type="hidden" name="old_img" value="{{ $shopInformation->contact_image}}">    
                                    <div class="form-group">
                                        <label>Announcement</label>
                                        <input type="text" id="announcement" name="announcement" class="form-control" value="{{$shopInformation->announcement}}" />
                                        @error('announcement') 
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Address Line 1</label>
                                        <input type="text" id="address1" name="address1" class="form-control" autocomplete="off" value="{{$shopInformation->address_line_1}}" >
                                        @error('address1') 
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Address Line 2</label>
                                        <input type="text" id="address2" name="address2" class="form-control" autocomplete="off" value="{{$shopInformation->address_line_2}}" >
                                        @error('address2') 
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Pincode</label>
                                        <input type="text" id="pincode" name="pincode" class="form-control" autocomplete="off" value="{{$shopInformation->pincode}}" >
                                        @error('pincode') 
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Mobile</label>
                                        <input  id="mobile" name="mobile" class="form-control" autocomplete="off" value="{{$shopInformation->mobile_number}}" >
                                        @error('mobile') 
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Contact Image</label>
                                        <input type="file" id="contact_image" name="contact_image" class="form-control" onChange="mainThamUrl(this)" />
                                        @error('contact_image') 
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror<br>
                                        <img src="{{ asset($shopInformation->contact_image) }}" height="100" width="100" id="mainThmb">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" id="email" name="email" class="form-control" autocomplete="off" value="{{$shopInformation->email}}" >
                                        @error('email') 
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Andriod</label>
                                        <input  id="andriod" name="andriod" class="form-control" autocomplete="off" value="{{$shopInformation->andriod_link}}" >
                                        @error('andriod') 
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Ios</label>
                                        <input type="text" id="ios" name="ios" class="form-control" autocomplete="off" value="{{$shopInformation->ios_link}}" >
                                        @error('ios') 
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Facebook</label>
                                        <input type="text" id="facebook" name="facebook" class="form-control" autocomplete="off" value="{{$shopInformation->facebook}}" >
                                        @error('facebook') 
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Twitter</label>
                                        <input type="text" id="twitter" name="twitter" class="form-control" autocomplete="off" value="{{$shopInformation->twitter}}" >
                                        @error('twitter') 
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Instagram</label>
                                        <input type="text" id="instagram" name="instagram" class="form-control" autocomplete="off" value="{{$shopInformation->instagram}}" >
                                        @error('instagram') 
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Youtube</label>
                                        <input type="text" id="youtube" name="youtube" class="form-control" autocomplete="off" value="{{$shopInformation->youtube}}">
                                        @error('youtube') 
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="clo-lg-12 col-md-12 d-flex align-items-center justify-content-center">
                                <div class="form-group">
                                    <button type="submit" id="btnSave" class="btn btn-success">Update</button>
                                </div>
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
              $('#mainThmb').attr('src',e.target.result).width(80).height(80);
          };
          reader.readAsDataURL(input.files[0]);
      }
  }
</script>