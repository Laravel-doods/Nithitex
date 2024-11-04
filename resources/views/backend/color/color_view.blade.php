@extends('admin.admin_master')
@section('admin')
@section('title')
    Product Color
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Product Color</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Master</li>
                        <li class="breadcrumb-item active">Product Color</li>
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
                        <div id="divColor" class="col-lg-4 col-md-4">
                            <form method="post" action="{{ route('color.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="exampleInputEmail1">
                                        Color Name</label>
                                    <input type="text" id="color" name="color"
                                        class="form-control  @error('color') is-invalid @enderror"
                                        title="Please Enter Color Name" placeholder="Enter Color Name" required />
                                    @error('color')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">
                                        Color Code
                                        <span id="color-code-help"
                                            style="display: inline;color: rgb(85, 41, 191);background: rgba(85, 41, 191, 0.28);padding: 4px;border-radius: 12px;font-size: 12px;">Color
                                            code should
                                            start with #</span>
                                    </label>
                                    <input type="text" id="code" name="code"
                                        class="form-control @error('code') is-invalid @enderror"
                                        title="Please Enter Color Code starting with #"
                                        placeholder="Enter Color Code (e.g., #FFFFFF)" required />
                                    @error('code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
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
    <h5>Product Color List</h5>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{-- <h3 class="card-title">Product Color List</h3> --}}
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Color Name</th>
                                        <th>Product Color Code</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyList">
                                    @php
                                        $serialNo = 1;
                                    @endphp
                                    @foreach ($colors as $item)
                                        <tr id="emptyRow" style="height: 25px;">
                                            <td>{{ $serialNo }}</td>
                                            <td>{{ $item->color_name }}</td>
                                            <td>{{ $item->color_code }}</td>
                                            <td>
                                                <a href="{{ route('color.edit', $item->id) }}"
                                                    class="btn btn-info btn-sm btn-flat" title="Edit Data">Edit</a>
                                                <a href="{{ route('color.delete', $item->id) }}"
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
