@extends('admin.admin_master')
@section('admin')
@section('title')
Policy
@endsection
<div class="page-content">
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h5>Company Policy</h5>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Settings</li>
                <li class="breadcrumb-item active">Policy</li>
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
                        <div id="divCategory" class="col-md-12">
                            <form method="post" action="{{ route('policy.store') }}" >
                                @csrf
                                <div class="form-group">
                                    <strong>
                                       Terms & Condition</strong>
                                       <textarea id="terms_condition" class="form-control" rows="10" name="terms_condition">@if($policy) {{$policy->terms_condition}}  @endif</textarea>
                                       @error('shortdescription') 
                                       <span class="text-danger">{{ $message }}</span>
                                       @enderror
                                </div>
                                <div class="form-group mt-5">
                                    <strong>
                                        Privacy Policy</strong>
                                       <textarea id="privacy_policy" class="form-control" rows="10" name="privacy_policy">@if($policy) {{$policy->privacy_policy}}  @endif</textarea>
                                       @error('shortdescription') 
                                       <span class="text-danger">{{ $message }}</span>
                                       @enderror
                                </div>
                                <div class="form-group mt-5">
                                    <strong>
                                       Return Policy</strong>
                                       <textarea id="return_policy" class="form-control" rows="10" name="return_policy">@if($policy) {{$policy->return_policy}}  @endif</textarea>
                                       @error('shortdescription') 
                                       <span class="text-danger">{{ $message }}</span>
                                       @enderror
                                </div>
                                <div class="form-group mt-5">
                                    <strong>
                                       Support Policy</strong>
                                       <textarea id="support_policy" class="form-control" rows="10" name="support_policy">@if($policy) {{$policy->support_policy}}  @endif</textarea>
                                       @error('shortdescription') 
                                       <span class="text-danger">{{ $message }}</span>
                                       @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="btnSave" class="btn btn-success">Save</button>
                                    {{-- <button type="button" class="btn btn-danger" id="btnClear" onclick="ClearData();">Clear</button> --}}
                                </div>
                            </form>
                        </div> 
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>

 
@endsection