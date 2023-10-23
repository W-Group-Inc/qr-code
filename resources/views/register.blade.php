@extends('frontLayout.app')
@section('title')
QR Code
@stop
@section('content')
<div class = "container">
    <div class="panel-heading">
       <div class="panel-title text-center">
          <h1 class="title">Generate QR Code</h1>
          <hr />
        </div>
    </div>
    @if (Session::has('message'))
     <div class="alert alert-{{(Session::get('status')=='error')?'danger':Session::get('status')}} " alert-dismissable fade in id="sessions-hide">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
       <strong>{{Session::get('status')}}!</strong> {!! Session::get('message') !!}
      </div>
    @endif 
    <form method='POST' action='generate-qr-code'  enctype="multipart/form-data" >  
        {!! csrf_field() !!}
            
            <div class="form-group  {{ $errors->has('name') ? 'has-error' : ''}}">
              <label for="name" class="cols-sm-2 control-label">Name</label>
              <div class="cols-sm-10">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                  <input type='text' class='form-control' id='name' name='name' placeholder='Enter your name' required>
                </div>
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
              </div>
            </div>

            <div class="form-group  {{ $errors->has('email') ? 'has-error' : ''}}">
              <label for="email" class="cols-sm-2 control-label">Company Email</label>
              <div class="cols-sm-10">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
                  <input type='email' class='form-control' id='first_name' name='email' placeholder='Enter your email' required>
                </div>
                 {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
              </div>
            </div>
            <div class="form-group  {{ $errors->has('department') ? 'has-error' : ''}}">
              <label for="department" class="cols-sm-2 control-label">Department</label>
              <div class="cols-sm-10">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-list fa" aria-hidden="true"></i></span>
                  <input type='text' class='form-control' id='department' name='department' placeholder='Enter your department' required>
                </div>
                 {!! $errors->first('department', '<p class="help-block">:message</p>') !!}
              </div>
            </div>

            <div class="form-group  {{ $errors->has('password') ? 'has-error' : ''}} ">
              <button class="btn btn-primary btn-lg btn-block register-button" type="submit" >Generate QR Code</button>
              
            </div>     
        </form>
  </div>
@endsection

@section('scripts')


@endsection