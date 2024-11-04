@extends('seller.seller_main_master')
@section('content')
@section('title')
Seller | Dashboard | India's No 1 Online Saree Shop - Nithitex
@endsection
@php
    $id = Auth::user()->id;
    $user = App\Models\User::find($id);
    $state = App\Models\State::orderby('state_name','ASC')->get();
    $seller = App\Models\Seller::where('email',Auth::user()->email)->first();
@endphp
<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="{{route('seller.home')}}">Home</a>
                </li>
                <li class="active">Seller Profile Update </li>
            </ul>
        </div>
    </div>
</div>
<!-- my account wrapper start -->
<div class="my-account-wrapper pt-120 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- My Account Page Start -->
                <div class="myaccount-page-wrapper">
                    <!-- My Account Tab Menu Start -->
                    <div class="row">
                        @include('seller.seller_sidebar')
                        <!-- My Account Tab Menu End -->
                        <!-- My Account Tab Content Start -->
                        <div class="col-lg-10 col-md-10">
                            <div class="myaccount-content">
                                <h3>Profile Update</h3>
                                {{-- {{ route('seller.profile.store') }} --}}
                                <form method="post" action="{{route('seller.profile.store')}}" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <strong>Name </strong>
                                                <input type="text" name="name" class="form-control" value="{{$user->name}}" autocomplete="off">                                
                                            </div><br>
                                    
                                            <div class="form-group">
                                                <strong>ShopName </strong>
                                                <input type="text" name="shop_name" class="form-control" value="{{$seller->shop_name}}" autocomplete="off">                                 
                                            </div><br>

                                            <div class="form-group">
                                                <strong>Email </strong>
                                                <input type="email" name="email" class="form-control" value="{{$user->email}}" readonly>                                
                                            </div><br>
                                    
                                            <div class="form-group">
                                                <strong>Phone / Google Pay / PhonePe Number</strong>
                                                <input type="text" name="phone" class="form-control" value="{{$user->phone}}" autocomplete="off">                                
                                            </div><br>  

                                            <div class="form-group">
                                                <strong for="exampleInputEmail1">Door No <span> </span></strong>
                                                <input type="text" name="door_no" class="form-control" id="door_no" placeholder="Enter Door no" value="{{ $user->door_no }}" autocomplete="off">
                                            </div><br>
                                            
                                            <div class="form-group">
                                                <strong for="exampleInputEmail1">Street  <span> </span></strong>
                                                <input type="text" name="street" class="form-control" id="street" placeholder="Enter Street Name" value="{{ $user->street_address }}" autocomplete="off">
                                            </div><br>

                                            <div class="form-group">
                                                <strong class="info-title" for="exampleInputEmail1">City <span> </span></strong>
                                                <input type="text" name="city" class="form-control" id="city" placeholder="Enter City Name" value="{{ $user->city_name }}" autocomplete="off">                                
                                            </div><br>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <strong>Bank Name</strong>
                                                <input type="text" name="bank_name" class="form-control" value="{{$seller->bank_name}}" autocomplete="off">                                
                                            </div><br>
    
                                            <div class="form-group">
                                                <strong>Bank Account Name</strong>
                                                <input type="text" name="bank_account_name" class="form-control" value="{{$seller->bank_account_name}}" autocomplete="off">                                
                                            </div><br>
                                    
                                            <div class="form-group">
                                                <strong>Bank Account Number</strong>
                                                <input type="text" name="bank_account_number" class="form-control" value="{{$seller->bank_account_number}}" autocomplete="off">                                
                                            </div><br>
    
                                            <div class="form-group">
                                                <strong>Bank IFSC Code</strong>
                                                <input type="text" name="bank_ifsc" class="form-control" value="{{$seller->bank_ifsc}}" autocomplete="off">                                
                                            </div><br>

                                            <div class="form-group">
                                                <strong class="info-title" for="exampleInputEmail1">State <span> </span></strong>
                                                <select id="state_name" name="state_name" class="form-select billing-address" title="Please Select State" >
                                                    <option value="">Select State</option>
                                                       @foreach ($state as $item)
                                                       <option value="{{$item->state_name}}" @if(auth::user()->state_name == $item->state_name) selected @endif>{{$item->state_name}}</option>
                                                       @endforeach
                                                   </select>                           
                                            </div><br>

                                            <div class="form-group">
                                                <strong class="info-title" for="exampleInputEmail1">Pincode <span> </span></strong>
                                                <input type="text" name="pincode" class="form-control" id="pincode" placeholder="Enter Pincode" value="{{ $user->pin_code }}" autocomplete="off">                          
                                            </div><br>

                                            <div class="form-group">
                                                <strong class="info-title" for="exampleInputEmail1">Seller Image <span> </span></strong>
                                                <input type="file" name="profile_photo_path" class="form-control" onChange="mainThamUrl(this)"><br>     
                                                <img src="@if($user->profile_photo_path == null) {{asset('profile.png')}} 
                                                @else{{ asset('upload/user_images/'.$user->profile_photo_path) }} @endif" height="100" width="120" id="mainThmb">                           
                                            </div><br>

                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-danger">Update</button>
                                    </div>
                                </form>
                            </div>  
                        </div> <!-- My Account Tab Content End -->
                    </div>
                </div> <!-- My Account Page End -->
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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

@endsection

