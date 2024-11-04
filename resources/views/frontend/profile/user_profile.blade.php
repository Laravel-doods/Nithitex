@extends('frontend.main_master')
@section('content')
@section('title')
    Dashboard | India's No 1 Online Saree Shop - Nithitex
@endsection
@php
    $id = Auth::user()->id;
    $user = App\Models\User::find($id);
    $state = App\Models\State::orderby('state_name', 'ASC')->get();
@endphp

<div class="breadcrumb-area bg-gray">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <ul>
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">Profile Update </li>
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
                        @include('frontend.common.user_sidebar')
                        <!-- My Account Tab Menu End -->
                        <!-- My Account Tab Content Start -->
                        <div class="col-lg-10 col-md-10">
                            <div class="myaccount-content">
                                <h3>Profile Update</h3>
                                <form method="post" action="{{ route('user.profile.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="hidden" name="old_img"
                                                value="{{ $user->profile_photo_path }}">
                                            <div class="form-group">
                                                <strong class="info-title" for="exampleInputEmail1">Name <span>
                                                    </span></strong>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ $user->name }}" autocomplete="off">
                                            </div>

                                            <div class="form-group">
                                                <strong class="info-title" for="exampleInputEmail1">Email <span>
                                                    </span></strong>
                                                <input type="email" name="email" class="form-control"
                                                    value="{{ $user->email }}" readonly>
                                            </div>

                                            <div class="form-group">
                                                <strong class="info-title" for="exampleInputEmail1">Phone <span>
                                                    </span></strong>
                                                <input type="text" name="phone" class="form-control"
                                                    value="{{ $user->phone }}" autocomplete="off">
                                            </div>
                                            <div class="form-group">
                                                <strong class="info-title" for="exampleInputEmail1">User Image <span>
                                                    </span></strong>
                                                <input type="file" name="profile_photo_path" class="form-control"
                                                    onChange="mainThamUrl(this)"><br>
                                                <img src="@if ($user->profile_photo_path == null) {{ asset('profile.png') }} 
                                                        @else{{ asset('upload/user_images/' . $user->profile_photo_path) }} @endif"
                                                    height="100" width="120" id="mainThmb">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <strong for="exampleInputEmail1">Door No <span> </span></strong>
                                                <input type="text" name="door_no" class="form-control" id="door_no"
                                                    placeholder="Enter Door no" value="{{ $user->door_no }}"
                                                    autocomplete="off">
                                            </div>

                                            <div class="form-group">
                                                <strong for="exampleInputEmail1">Street <span> </span></strong>
                                                <input type="text" name="street" class="form-control" id="street"
                                                    placeholder="Enter Street Name" value="{{ $user->street_address }}"
                                                    autocomplete="off">
                                            </div>

                                            <div class="form-group">
                                                <strong class="info-title" for="exampleInputEmail1">City <span>
                                                    </span></strong>
                                                <input type="text" name="city" class="form-control" id="city"
                                                    placeholder="Enter City Name" value="{{ $user->city_name }}"
                                                    autocomplete="off">
                                            </div>

                                            <div class="form-group">
                                                <strong class="info-title" for="exampleInputEmail1">State <span>
                                                    </span></strong>
                                                <select id="state_name" name="state_name"
                                                    class="form-select billing-address" title="Please Select State">
                                                    <option value="">Select State</option>
                                                    @foreach ($state as $item)
                                                        <option value="{{ $item->state_name }}"
                                                            @if (auth::user()->state_name == $item->state_name) selected @endif>
                                                            {{ $item->state_name }}</option>
                                                    @endforeach
                                                </select>
                                                
                                            </div>

                                            <div class="form-group">
                                                <strong class="info-title" for="exampleInputEmail1">Pincode <span>
                                                    </span></strong>
                                                <input type="text" name="pincode" class="form-control" id="pincode"
                                                    placeholder="Enter Pincode" value="{{ $user->pin_code }}"
                                                    autocomplete="off">
                                            </div>
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
<script type="text/javascript">
    function mainThamUrl(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#mainThmb').attr('src', e.target.result).width(80).height(80);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
