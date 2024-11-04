@extends('admin.admin_master')
@section('admin')
@section('title')
    Assign User Role List
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Assign User Role List</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Master</li>
                        <li class="breadcrumb-item active">Assign User Role List</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('assign_role_users/create') }}" class="btn btn-dark">Assign Roles to User</a>
                    {{-- <h3 class="card-title">User List</h3> --}}
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Staff Name</th>
                                        <th scope="col">Roles</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($users->count())
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>
                                                    @if (count($user->roles->pluck('name')->toArray()) > 0)
                                                        {{ implode(',', $user->roles->pluck('name')->toArray()) }}
                                                    @else
                                                        No Role Assigned
                                                    @endif
                                                </td>
                                                <td>
                                                    <form action="{{ url('assign_role_users') }}/{{ $user->id }}"
                                                        method="POST">
                                                        <a href="{{ url('assign_role_users') }}/{{ $user->id }}"
                                                            class="btn btn-info">Edit</a>
                                                        @csrf
                                                        @method('DELETE')
                                                        <button typy="Submit" class="btn btn-danger" onclick="return confirm('Are you sure want to delete this record?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
