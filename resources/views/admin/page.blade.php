@extends('admin.admin_master')
@section('admin')
@section('title')
Gym Plus
@endsection

<div class="page-content">
  <div class="page-bar">
    <div class="page-title-breadcrumb">
      <div class=" pull-left">
        <div class="page-title">Form Validation</div>
      </div>
      <ol class="breadcrumb page-breadcrumb pull-right">
        <li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
            href="{{route('admin.dashboard')}}">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
        </li>
        <li><a class="parent-item" href="#">Forms</a>&nbsp;<i class="fa fa-angle-right"></i>
        </li>
        <li class="active">Form Validation</li>
      </ol>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="card card-box">
        <div class="card-head">
          <header>Validation States</header>
          <button id="panel-button"
            class="mdl-button mdl-js-button mdl-button--icon pull-right"
            data-upgraded=",MaterialButton">
            <i class="material-icons">more_vert</i>
          </button>
          <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
            data-mdl-for="panel-button">
            <li class="mdl-menu__item"><i class="material-icons">assistant_photo</i>Action
            </li>
            <li class="mdl-menu__item"><i class="material-icons">print</i>Another action
            </li>
            <li class="mdl-menu__item"><i class="material-icons">favorite</i>Something else
              here</li>
          </ul>
        </div>
        <div class="card-body" id="bar-parent">
          <form action="#" class="form-horizontal">
            <div class="form-body">
              <h3 class="form-section">Basic validation States</h3>
              <div class="form-group row has-success">
                <label class="control-label col-md-3" for="inputSuccess">Text</label>
                <div class="col-md-4">
                  <input type="text" class="form-control" id="inputSuccess" />
                  <span class="help-block"> Success Message </span>
                </div>
              </div>
              <div class="form-group row has-warning">
                <label class="control-label col-md-3" for="inputWarning">Text</label>
                <div class="col-md-4">
                  <input type="text" class="form-control" id="inputWarning" />
                  <span class="help-block"> Warning Message </span>
                </div>
              </div>
              <div class="form-group row has-error">
                <label class="control-label col-md-3" for="inputError">Text</label>
                <div class="col-md-4">
                  <input type="text" class="form-control" id="inputError" />
                  <span class="help-block"> Error Message </span>
                </div>
              </div>
              <h3 class="form-section">Validation States With Icons</h3>
              <div class="form-group row has-warning">
                <label class="control-label col-md-3">Input with warning</label>
                <div class="col-md-4">
                  <div class="input-icon right">
                    <i class="fa fa-exclamation tooltips"
                      data-original-title="please write a valid email"></i>
                    <input type="text" class="form-control" /> </div>
                </div>
              </div>
              <div class="form-group row has-error">
                <label class="control-label col-md-3">Input with error</label>
                <div class="col-md-4">
                  <div class="input-icon right">
                    <i class="fa fa-warning tooltips"
                      data-original-title="please write a valid email"></i>
                    <input type="text" class="form-control" /> </div>
                </div>
              </div>
              <div class="form-group row has-success">
                <label class="control-label col-md-3">Input with success</label>
                <div class="col-md-4">
                  <div class="input-icon right">
                    <i class="fa fa-check tooltips"
                      data-original-title="success input!"></i>
                    <input type="text" class="form-control" /> </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="card card-box">
        <div class="card-head">
          <header>Basic Validation</header>
          <button id="panel-button1"
            class="mdl-button mdl-js-button mdl-button--icon pull-right"
            data-upgraded=",MaterialButton">
            <i class="material-icons">more_vert</i>
          </button>
          <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
            data-mdl-for="panel-button1">
            <li class="mdl-menu__item"><i class="material-icons">assistant_photo</i>Action
            </li>
            <li class="mdl-menu__item"><i class="material-icons">print</i>Another action
            </li>
            <li class="mdl-menu__item"><i class="material-icons">favorite</i>Something else
              here</li>
          </ul>
        </div>
        <div class="card-body" id="bar-parent1">
          <form action="#" id="form_sample_1" class="form-horizontal">
            <div class="form-body">
              <div class="form-group row">
                <label class="control-label col-md-3">Name
                  <span class="required"> * </span>
                </label>
                <div class="col-md-4">
                  <input type="text" name="name" data-required="1"
                    class="form-control" /> </div>
              </div>
              <div class="form-group row">
                <label class="control-label col-md-3">Email
                  <span class="required"> * </span>
                </label>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="fa fa-envelope"></i>
                    </span>
                    <input type="text" class="form-control" name="email"
                      placeholder="Email Address"> </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="control-label col-md-3">URL
                  <span class="required"> * </span>
                </label>
                <div class="col-md-4">
                  <input name="url" type="text" class="form-control" />
                  <span class="help-block"> e.g: http://www.demo.com or
                    http://demo.com </span>
                </div>
              </div>
              <div class="form-group row">
                <label class="control-label col-md-3">Number
                  <span class="required"> * </span>
                </label>
                <div class="col-md-4">
                  <input name="number" type="text" class="form-control" /> </div>
              </div>
              <div class="form-group row">
                <label class="control-label col-md-3">Credit Card
                  <span class="required"> * </span>
                </label>
                <div class="col-md-4">
                  <input name="creditcard" type="text" class="form-control" />
                  <span class="help-block"> e.g: 5500 0000 0000 0004 </span>
                </div>
              </div>
              <div class="form-group row">
                <label class="control-label col-md-3">Occupation&nbsp;&nbsp;</label>
                <div class="col-md-4">
                  <input name="occupation" type="text" class="form-control" />
                  <span class="help-block"> optional field </span>
                </div>
              </div>
              <div class="form-group row">
                <label class="control-label col-md-3">Select
                  <span class="required"> * </span>
                </label>
                <div class="col-md-4">
                  <select class="form-select" name="select">
                    <option value="">Select...</option>
                    <option value="Category 1">Category 1</option>
                    <option value="Category 2">Category 2</option>
                    <option value="Category 3">Category 5</option>
                    <option value="Category 4">Category 4</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="offset-md-3 col-md-9">
                <button type="submit" class="btn btn-info m-r-20">Submit</button>
                <button type="button" class="btn btn-default">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="card card-box">
        <div class="card-head">
          <header>Validation with Icons</header>
          <button id="panel-button2"
            class="mdl-button mdl-js-button mdl-button--icon pull-right"
            data-upgraded=",MaterialButton">
            <i class="material-icons">more_vert</i>
          </button>
          <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
            data-mdl-for="panel-button2">
            <li class="mdl-menu__item"><i class="material-icons">assistant_photo</i>Action
            </li>
            <li class="mdl-menu__item"><i class="material-icons">print</i>Another action
            </li>
            <li class="mdl-menu__item"><i class="material-icons">favorite</i>Something else
              here</li>
          </ul>
        </div>
        <div class="card-body" id="bar-parent2">
          <form action="#" id="form_sample_2" class="form-horizontal">
            <div class="form-body">
              <div class="form-group row  margin-top-20">
                <label class="control-label col-md-3">Name
                  <span class="required"> * </span>
                </label>
                <div class="col-md-4">
                  <div class="input-icon right">
                    <i class="fa"></i>
                    <input type="text" class="form-control" name="name" /> </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="control-label col-md-3">Email
                  <span class="required"> * </span>
                </label>
                <div class="col-md-4">
                  <div class="input-icon right">
                    <i class="fa"></i>
                    <input type="text" class="form-control" name="email" /> </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="control-label col-md-3">URL
                  <span class="required"> * </span>
                </label>
                <div class="col-md-4">
                  <div class="input-icon right">
                    <i class="fa"></i>
                    <input type="text" class="form-control" name="url" /> </div>
                  <span class="help-block"> e.g: http://www.demo.com or
                    http://demo.com </span>
                </div>
              </div>
              <div class="form-group row">
                <label class="control-label col-md-3">Number
                  <span class="required"> * </span>
                </label>
                <div class="col-md-4">
                  <div class="input-icon right">
                    <i class="fa"></i>
                    <input type="text" class="form-control" name="number" /> </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="control-label col-md-3">Credit Card
                  <span class="required"> * </span>
                </label>
                <div class="col-md-4">
                  <div class="input-icon right">
                    <i class="fa"></i>
                    <input type="text" class="form-control" name="creditcard" />
                  </div>
                  <span class="help-block"> e.g: 5500 0000 0000 0004 </span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="offset-md-3 col-md-9">
                <button type="submit" class="btn btn-info m-r-20">Submit</button>
                <button type="button" class="btn btn-default">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
 
@endsection