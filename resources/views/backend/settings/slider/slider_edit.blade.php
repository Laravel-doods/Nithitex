@extends('admin.admin_master')
@section('admin')
@section('title')
    Slider
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Edit Home Slider</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                        <li class="breadcrumb-item active">Slider</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('slider.update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Slider For</label>
                                    <div>
                                        <span class="pl-2">
                                            <input type="radio" name="rdSliderFor" id="rdSliderFor" value="1"
                                                @if ($slider->userrole_id == 1) checked @endif><span
                                                class="pl-1">CUSTOMER</span>
                                        </span>
                                        <span class="pl-2">
                                            <input type="radio" name="rdSliderFor" id="rdSliderFor" value="2"
                                                @if ($slider->userrole_id == 2) checked @endif><span
                                                class="pl-1">SELLER</span>
                                        </span>
                                        <span class="pl-2">
                                            <input type="radio" name="rdSliderFor" id="rdSliderFor" value="0"
                                                @if ($slider->userrole_id == 0) checked @endif><span
                                                class="pl-1">BOTH</span>
                                        </span>
                                        @error('rdSliderFor')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Redirect Website Url</label>
                                <input type="text" class="form-control" name="txtRedirectWebsiteUrl"
                                    id="txtRedirectWebsiteUrl" placeholder="Enter Redirect Website Url"
                                    value="{{ $slider->web_redirect_url }}">
                                @error('txtRedirectWebsiteUrl')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label>Redirect App Url</label>
                                <select class="form-select" name="txtRedirectAppUrl" id="txtRedirectAppUrl">
                                    <option value="select">Select App Url</option>
                                    <option value="newArrival" @if ($slider->app_redirect_url == 'newArrival') selected @endif>
                                        newArrival
                                    </option>
                                    <option value="featuredProduct" @if ($slider->app_redirect_url == 'featuredProduct') selected @endif>
                                        featuredProduct
                                    </option>
                                    <option value="bestSelling" @if ($slider->app_redirect_url == 'bestSelling') selected @endif>
                                        bestSelling
                                    </option>
                                    <option value="offerProduct" @if ($slider->app_redirect_url == 'offerProduct') selected @endif>
                                        offerProduct
                                    </option>
                                    <option value="category" @if ($slider->app_redirect_url == 'category') selected @endif>category
                                    </option>
                                    <option value="resellerRegister" @if ($slider->app_redirect_url == 'resellerRegister') selected @endif>
                                        resellerRegister
                                    </option>
                                    <option value="todaysOffer" @if ($slider->app_redirect_url == 'todaysOffer') selected @endif>
                                        todaysOffer
                                    </option>
                                </select>
                                @error('txtRedirectAppUrl')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" name="id" value="{{ $slider->id }}">
                                <input type="hidden" name="old_img" value="{{ $slider->slider_image }}">
                                <div class="form-group">
                                    <label>Slider Image</label>
                                    <input type="file" id="slider_image" name="slider_image" class="form-control"
                                        onChange="mainThamUrl(this)" />
                                    @error('slider_image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <br>
                                    <img src="{{ asset($slider->slider_image) }}" height="100" width="220"
                                        id="mainThmb">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>&nbsp;</label>
                                <div class="form-group">
                                    <button type="submit" id="btnSave" class="btn btn-success">Save</button>
                                </div>
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
    function mainThamUrl(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#mainThmb').attr('src', e.target.result).width(220).height(100);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
