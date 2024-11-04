@extends('admin.admin_master')
@section('admin')
@section('title')
    Notification
@endsection

<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Notification</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Master</li>
                        <li class="breadcrumb-item active">Notification</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ route('send.notification') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-12">
                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" id="title" name="title" class="form-control"
                                title="Please Enter Title" placeholder="Enter Title" autocomplete="off" required />
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="form-group">
                            <label for="content">Content <span class="text-danger">*</span></label>
                            <input type="text" id="content" name="content" class="form-control"
                                title="Please Enter Content" placeholder="Enter Content" autocomplete="off" required />
                            @error('content')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" id="notifyImage" name="notifyImage" class="form-control"
                                onChange="mainThamUrl(this)" />
                            @error('notifyImage')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <br>
                            <img src="" id="mainThmb">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="form-group">
                            <label for="users">Select Users</label>
                            <select id="users" name="users[]" class="form-control" title="Please Select Users"
                                multiple>
                                <option value="0">Select Users</option>
                                @foreach ($users as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} - ({{ $item->userrole_id == 1 ? 'Customer' : 'Reseller' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('colors')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3 text-right">
                    <button type="submit" id="btnSave" class="btn btn-success">Send</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
<script src="{{ asset('backend/assets/js/common/jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
<link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/select2.min.css') }}">
<script>
    document.addEventListener('DOMContentLoaded', () => {
        $("#users").select2();
    });

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
