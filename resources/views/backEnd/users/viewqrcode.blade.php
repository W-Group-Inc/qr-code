@extends('frontLayout.app')
@section('title')
QR Code
@stop

@section('content')
<!-- qr code  -->
<div class=" text-center">
@if($id)
  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->color(0, 0, 0, 0.1)->merge(url('images/icon.png'), .1, true)->backgroundColor(255, 255, 255, 0.82)->size(500)->generate($id)) !!} ">
  <h2 style='font-color:black;'>{{$employee->name}}</h2>
  <strong><p style='color:black;'>This is your qr code , Download it into your mobile</p></strong>

  @endif
  <a href="data:image/png;base64, {!! base64_encode(QrCode::format('png')->color(0, 0, 0, 0.1)->merge(url('images/icon.png'), .1, true)->backgroundColor(255, 255, 255, 0.82)->size(500)->generate($id)) !!} " download><button type="submit"  class="btn btn-primary sub6">Download QR Code</button></a>
</div>

<!--   end qr code -->


@stop

@section('scripts')
@endsection