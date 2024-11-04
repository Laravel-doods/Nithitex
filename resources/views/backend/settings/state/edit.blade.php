@extends('admin.admin_master')
@section('admin')
@section('title')
State Master
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h5>State Master</h5>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Settings</li>
                <li class="breadcrumb-item active">State Master</li>
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
                            <form method="post" action="{{ route('state.update') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{$state->id}}">
                                <div class="form-group">
                                    <label>State Name</label>
                                    <input type="text" id="state_name" name="state_name" class="form-control" autocomplete="off" value="{{$state->state_name}}" required>
                                        @error('state_name') 
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                            <div class="form-group">
                                <label>State Short Name</label>
                                <input type="text" id="short_name" name="short_name" class="form-control"  value="{{$state->iso2}}" required/>
                                    @error('short_name') 
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                            </div>
                            <div class="form-group">
                                <label>Shipping Charge</label>
                                <input type="text" id="shipping_charge" name="shipping_charge" class="form-control" value="{{$state->shipping_charge}}"  required/>
                                    @error('shipping_charge') 
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                            </div>
                            <div class="form-group">
                                <label>Cash On Delivery</label>
                                <input type="text" id="cod_charge" name="cod_charge" class="form-control" value="{{$state->cod_charge}}"  required/>
                                    @error('cod_charge') 
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
</div>

 
@endsection
