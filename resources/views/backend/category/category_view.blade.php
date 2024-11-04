@extends('admin.admin_master')
@section('admin')
@section('title')
    Product Category
@endsection

<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Product Category</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Master</li>
                        <li class="breadcrumb-item active">Product Category</li>
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
                        <div id="divCategory" class="col-lg-4 col-md-4">
                            <form method="post" action="{{ route('category.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="txtDepartmentName">
                                        Main Category <span class="text-danger">*</span></label>
                                    <select id="ddlMainCategory" name="ddlMainCategory" class="form-control"
                                        title="Please Select MainCategory " required>
                                        <option value="" hidden>Select Main Category</option>
                                        @foreach ($main_category as $item)
                                            <option value="{{ $item->id }}">{{ $item->main_category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ddlMainCategory')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="category">
                                        Category Name<span class="text-danger">*</span></label>
                                    <input type="text" id="category" name="category" class="form-control"
                                        title="Please Enter Category Name" placeholder="Enter Category Name"
                                        autocomplete="off" required />
                                    @error('category')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="txtCategoryCode">
                                        Category Code</label>
                                    <input type="text" id="txtCategoryCode" name="txtCategoryCode"
                                        class="form-control" title="Please Enter Category Code"
                                        placeholder="Enter Category Code" autocomplete="off" />
                                    @error('txtCategoryCode')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Category Image<span class="text-danger">*</span></label>
                                    <input type="file" id="category_image" name="category_image" class="form-control"
                                        onChange="mainThamUrl(this)" required />
                                    @error('category_image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <br>
                                    <img src="" id="mainThmb">
                                </div>
                                <div class="form-group">
                                    <label>Category Description</label>
                                    <textarea id="category_description" name="category_description" class="form-control" autocomplete="off"></textarea>
                                    @error('category_description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="btnSave" class="btn btn-success">Save</button>
                                </div>
                            </form>
                        </div>

                        {{-- <div id="divTodayOffer" class="col-lg-8 col-md-8">
                            <div class="container">
                                <div class="card mt-3 shadow border border-primary">
                                    <div class="card-body">

                                        <div class="row" id="rTodayOffer">
                                            @foreach ($categories as $item)
                                                @if ($item->is_today_offer == 1)
                                                    <div class="col-12 mb-3 text-center">
                                                        <h5 class="text-danger font-weight-bold">Today Offer</h5>
                                                    </div>
                                                    <div class="col-4">
                                                        <label for="">Category</label>
                                                        <input type="text" class="form-control" disabled
                                                            value="{{ $item->category_name }}">

                                                    </div>
                                                    <div class="col-4">
                                                        <label for="">offer(%)</label>
                                                        <input type="text" class="form-control" disabled
                                                            value="{{ $item->offer }}">
                                                    </div>

                                                    <div class="col-4 text-center">
                                                        <div class="form-group mt-3">
                                                            <button type="button" id="offerbtnSave"
                                                                class="btn btn-Danger mt-3"
                                                                onclick="removeTodayOffer();">Inactive</button>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-12">
                                                        <hr>
                                                    </div>
                                                @endif
                                                
                                            @endforeach
                                        </div>
                                        <form action="{{ route('create.today.offer') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12 mt-3 text-center">

                                                    <h5 class="text-success font-weight-bold">Add Offer</h5>
                                                </div>

                                                <div class="col-4 py-3">
                                                    <div class="form-group">
                                                        <label for="ddlCategory"> Category <span
                                                                class="text-danger">*</span></label>
                                                        <select id="ddlCategory" name="ddlCategory"
                                                            class="form-control" title="Please Select MainCategory "
                                                            required>
                                                            <option value="0" hidden>Select Category</option>
                                                            @foreach ($categories as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->category_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('ddlCategory')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4 py-3">
                                                    <div class="form-group">
                                                        <label for="offer">Offer(%) <span
                                                            class="text-danger">*</span></label>
                                                        <input type="number" min="0" id="offer"
                                                            name="offer" class="form-control"
                                                            autocomplete="off" required />
                                                        @error('offer')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4 py-3 text-center">
                                                    <div class="form-group mt-3">
                                                        <button type="submit" id="offerbtnSave"
                                                            class="btn btn-success mt-3">Active</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>


                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    <h5 class="pb-3 pt-5">PRODUCT CATEGORY LIST</h5>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{-- <h3 class="card-title">Product Category List</h3> --}}
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category Image</th>
                                        <th>Category Name</th>
                                        <th>Main Category Name</th>
                                        <th>Category Code</th>
                                        <th>Categroy Description</th>
                                        <th>Weight(kg)</th>
                                        <th>Today Offer</th>
                                        <th>Offer Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyList">
                                    @php
                                        $serialNo = 1;
                                    @endphp
                                    @foreach ($categories as $item)
                                        <tr id="emptyRow" style="height: 25px;">
                                            <td>{{ $serialNo }}</td>
                                            <td><img src="{{ asset($item->category_image) }}"
                                                    style="width: 60px; height: 50px;"></td>
                                            <td>{{ $item->category_name }}</td>
                                            <td>{{ $item->main_category->main_category_name }}</td>
                                            <td>{{ $item->category_code }}</td>
                                            <td>{{ $item->category_description }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <input type="number" name="weight" id="weight_{{ $item->id }}" class="form-control" min="0"
                                                    title="Please enter Weight" value="{{ $item->weight }}" style="width: 70px;"> 
                                                    <button class="btn btn-info" data-id="{{ $item->id }}" onclick="updateWeight({{ $item->id }});">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group mb-3">
                                                    <input type="number" min="0"
                                                        id="offer{{ $item->id }}" name="offer"
                                                        class="form-control"
                                                        value="{{ $item->offer == 0 ? '' : $item->offer }}"
                                                        autocomplete="off" required
                                                        {{ $item->is_today_offer == 1 ? 'disabled' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group text-center">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox"
                                                            class="custom-control-input chkOfferStatus"
                                                            id="chkOfferStatus{{ $item->id }}"
                                                            data-itemid="{{ $item->id }}" name="chkOfferStatus"
                                                            {{ $item->is_today_offer == 1 ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="chkOfferStatus{{ $item->id }}"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('category.edit', $item->id) }}"
                                                    class="btn btn-info btn-sm btn-flat" title="Edit Data">Edit</a>
                                                <a href="{{ route('category.delete', $item->id) }}"
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

<script src="{{ asset('backend/assets/js/common/jquery.min.js') }}"></script>

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

    function updateWeight(id) {
    var updatedWeight = $('#weight_' + id).val(); // Get the weight by item ID

    // SweetAlert confirmation
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to update the weight!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('category.update-weight') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    category_id: id,
                    weight: updatedWeight
                },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire(
                                'Updated!',
                                response.message,
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'Something went wrong. Please try again later.',
                            'error'
                        );
                    }
                });
            }
        });
    }

    function removeTodayOffer() {

        $.ajax({
            url: "remove/todayoffer",
            type: "GET",
            success: function(response) {
                $("#rTodayOffer").hide();

                toastr.success(response.success);
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true
                }
            },
            error: function(xhr) {

            },
        });
    }
    $(document).ready(function() {
        $('.chkOfferStatus').change(function() {
            var itemId = $(this).data('itemid');
            var offerInput = $('#offer' + itemId);
            var offerValue = offerInput.val().trim();
            var status = $(this).is(':checked') ? 1 : 0;

            if ($(this).is(':checked')) {
                if (offerValue === '' || parseInt(offerValue) === 0 || parseInt(offerValue) > 100) {
                    if (offerValue === '' || parseInt(offerValue) === 0) {
                        toastr.error('Offer value cannot be empty or zero.');
                    } else if (parseInt(offerValue) > 100) {
                        toastr.error('Offer value cannot be greater than 100.');
                    }
                    offerInput.val('');
                    offerInput.focus();
                    $(this).prop('checked', false);
                } else {

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to create this offer?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, create it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Call AJAX request after confirmation
                            $.ajax({
                                url: "create/todayoffer",
                                type: "POST",
                                dataType: "json",
                                data: {
                                    id: itemId,
                                    offer: offerValue
                                },
                                success: function(response) {
                                    if (response.success) {
                                        toastr.success(response.message);

                                        // window.location.reload();
                                        offerInput.prop('disabled',
                                            true); // Disable the input
                                    } else {
                                        toastr.error(response.message);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    toastr.error(
                                        'An error occurred. Please try again.');
                                }
                            });
                        } else {
                            $(this).prop('checked', false);
                        }
                    });
                }

            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to remove this offer?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "remove/todayoffer",
                            type: "POST",
                            dataType: "json",
                            data: {
                                id: itemId,
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    // window.location.reload();
                                    offerInput.prop('disabled', false);
                                    offerInput.val("");
                                } else {
                                    toastr.error(response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                toastr.error(
                                    'An error occurred. Please try again.');
                            }
                        });
                    } else {
                        $(this).prop('checked', true);
                    }
                });
            }
        });
    });
</script>
