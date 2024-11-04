@extends('admin.admin_master')
@section('admin')
@section('title')
    User
@endsection
<style>
    ul {
        list-style: none;
        margin: 5px 10px;
    }

    li {
        margin: 10px 0;
    }
</style>
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Create Role</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Master</li>
                        <li class="breadcrumb-item active">Create Role</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul class="p-0 m-0" style="list-style: none;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <form action="{{ url('role') }}" method="POST">
                        <div class="row">
                            @csrf
                            <div class="col-md-6">
                                <label for="exampleInputName" class="form-label">Role Name</label>
                                <input name="name" type="text" class="form-control" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <li><input type="checkbox">All<ul>
                                            @if ($permissionGroups->count())
                                                @foreach ($permissionGroups as $permissionGroup)
                                                    <li>
                                                        <input type="checkbox">
                                                        <b> {{ $permissionGroup->name }}</b>
                                                        @if ($permissionGroup->permissions->count())
                                                            @foreach ($permissionGroup->permissions as $permission)
                                                                <ul>
                                                                    <li><input value="{{ $permission->id }}"
                                                                            name="permission_ids[]" type="checkbox">
                                                                        <label>{{ $permission->name }}</label>
                                                                    </li>
                                                                </ul>
                                                            @endforeach
                                                        @endif
                                                @endforeach
                                            @endif
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Role</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('input[type="checkbox"]').change(function(e) {
        var checked = $(this).prop("checked"),
            container = $(this).parent(),
            siblings = container.siblings();

        container.find('input[type="checkbox"]').prop({
            indeterminate: false,
            checked: checked
        });

        function checkSiblings(el) {
            var parent = el.parent().parent(),
                all = true;


            el.siblings().each(function() {
                return all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);

            });

            if (all && checked) {
                parent.children('input[type="checkbox"]').prop({
                    indeterminate: false,
                    checked: checked
                });
                checkSiblings(parent);

            } else if (all && !checked) {
                parent.children('input[type="checkbox"]').prop("checked", checked);
                parent.children('input[type="checkbox"]').prop("indeterminate",
                    (parent.find('input[type="checkbox"]:checked').length > 0));

            } else {
                el.parents("li").children('input[type="checkbox"]').prop({
                    indeterminate: true,
                    checked: false
                });
            }

        }

        checkSiblings(container);
    });

    $(document).ready(function() {
        $("form").submit(function() {
            if ($('input:checkbox').filter(':checked').length < 1) {
                alert("Please check at least one Permission!");
                return false;
            }
        });
    });
</script>

@endsection
