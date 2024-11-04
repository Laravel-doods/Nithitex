@extends('admin.admin_master')
@section('admin')
@section('title')
    About Us
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>About Us</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                        <li class="breadcrumb-item active">About Us</li>
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
                        <div id="divCategory" class="col-lg-12 col-md-12">
                            <form method="post" action="{{ route('about.store') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="hdAboutId" id="hdAboutId" value="{{ $about->id }}">
                                <div class="form-group">
                                    <label>About Description</label>
                                    <textarea id="about_description" name="about_description" class="form-control" autocomplete="off"> @if ($about)
{{ $about->about_description }}
@endif </textarea>
                                    @error('about_description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>About Image</label>
                                    <input type="file" id="about_image" name="about_image" class="form-control"
                                        onChange="mainThamUrl(this)" />
                                    @error('about_image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <br>
                                    <img src="@if ($about) {{ asset($about->about_image) }} @endif"
                                        height="100" width="100" id="mainThmb">
                                </div>
                                <div class="form-group">
                                    <label>Popup Image</label>
                                    <input type="file" id="popup_image" name="popup_image" class="form-control"
                                        onChange="popupUrl(this)" />
                                    @error('popup_image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <br>
                                    @if ($about->popup_image)
                                        <img src="{{ asset($about->popup_image) }}" height="100" width="100"
                                            id="popup">
                                        <button type="button" class="btn btn-danger" id="btnClear"
                                            onclick="deltePopupImage();">Delete Popup Image</button>
                                    @else
                                        <img src="{{ asset('upload/products/about-us/empty.jpg') }}" height="100"
                                            width="100">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="btnSave" class="btn btn-success">Save</button>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    function mainThamUrl(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#mainThmb').attr('src', e.target.result).width(80).height(80);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function popupUrl(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#popup').attr('src', e.target.result).width(80).height(80);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function deltePopupImage() {
        var about_id = $("#hdAboutId").val();
        $.ajax({
            url: "/settings/deletepopupimage",
            type: "POST",
            data: {
                about_id: about_id,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            dataType: "json",
            success: function(data) {
                if (data.delete_popup.alert == 'success') {
                    Swal.fire({
                        title: "Deleted successfully!",
                        text: "Popup image deleted successfully!",
                        icon: "success",
                        customClass: {
                            confirmButton: "btn btn-success",
                        },
                    })
                }
                window.location.reload();
            },
        });
    }
</script>
