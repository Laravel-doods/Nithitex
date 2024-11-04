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
                    <h5>Home Slider Setup</h5>
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
                    <form method="post" action="{{ route('slider.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Slider For</label>
                                    <div>
                                        <span class="pl-2">
                                            <input type="radio" name="rdSliderFor" id="rdSliderFor"
                                                value="1"><span class="pl-1">CUSTOMER</span>
                                        </span>
                                        <span class="pl-2">
                                            <input type="radio" name="rdSliderFor" id="rdSliderFor"
                                                value="2"><span class="pl-1">SELLER</span>
                                        </span>
                                        <span class="pl-2">
                                            <input type="radio" name="rdSliderFor" id="rdSliderFor" value="0"
                                                checked><span class="pl-1">BOTH</span>
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
                                    value="{{ old('txtRedirectWebsiteUrl') }}">
                                @error('txtRedirectWebsiteUrl')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label>Redirect App Url</label>
                                <select class="form-select" name="txtRedirectAppUrl" id="txtRedirectAppUrl">
                                    <option value="select">Select App Url</option>
                                    <option value="newArrival">newArrival</option>
                                    <option value="featuredProduct">featuredProduct</option>
                                    <option value="bestSelling">bestSelling</option>
                                    <option value="offerProduct">offerProduct</option>
                                    <option value="category">category</option>
                                    <option value="resellerRegister">resellerRegister</option>
                                    <option value="todaysOffer">todaysOffer</option>
                                </select>
                                @error('txtRedirectAppUrl')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mt-2">
                                <div class="form-group">
                                    <label>Slider Image</label>
                                    <input type="file" id="slider_image" name="slider_image" class="form-control"
                                        onChange="mainThamUrl(this)" />
                                    @error('slider_image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <br>
                                    <img src="" id="mainThmb">
                                </div>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label>&nbsp;</label>
                                <div class="form-group">
                                    <button type="submit" id="btnSave" class="btn btn-success">Save</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <h5>Slider List</h5>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Slider Image</th>
                                        <th>Slider For</th>
                                        <th>Redirect Website Url</th>
                                        <th>Redirect App Url</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyList">
                                    @php
                                        $serialNo = 1;
                                    @endphp
                                    @foreach ($slider as $item)
                                        <tr id="emptyRow" style="height: 25px;">
                                            <td>{{ $serialNo }}</td>
                                            <td><a href="{{ asset($item->slider_image) }}" target="_blank"><img
                                                        src="{{ asset($item->slider_image) }}"
                                                        style="width:50%; height: auto; border-radius: 0%;"></a>
                                            </td>
                                            <td>{{ $item->userrole_id == 0 ? 'BOTH' : ($item->userrole_id == 1 ? 'CUSTOMER' : 'RESELLER') }}
                                            </td>
                                            <td>{{ $item->web_redirect_url }}</td>
                                            <td>{{ $item->app_redirect_url }}</td>
                                            <td>
                                                <a href="{{ route('slider.edit', $item->id) }}"
                                                    class="btn btn-info btn-sm btn-flat" title="Edit Data">Edit</a>
                                                <a href="{{ route('slider.delete', $item->id) }}"
                                                    class="btn btn-danger btn-sm btn-flat" title="Delete Data"
                                                    id="delete">Delete</a>
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
<script type="text/javascript">
    function mainThamUrl(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#mainThmb').attr('src', e.target.result).width(120).height(80);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
