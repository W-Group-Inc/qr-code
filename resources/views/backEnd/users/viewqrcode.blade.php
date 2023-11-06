@extends('frontLayout.app')
@section('title')
QR Code
@stop

@section('content')
<!-- qr code  -->
<div class='row'>
  <div class='col-md-12'>
<div class="text-center">
@if($id)
  @php
    $qrCode = QrCode::format('png')
        ->color(0, 0, 0, 0.1)
        ->merge(url('images/icon.png'), .1, true)
        ->backgroundColor(255, 255, 255, 0.82)
        ->size(400)
        ->generate($employee->position);
  @endphp

  <img src="data:image/png;base64, {!! base64_encode($qrCode) !!} ">
  <h2 style='color: black;'>{{$employee->name}}</h2>
  <strong><p style='color: black;'>This is your QR code. Download it to your mobile.</p></strong>
  @endif
<a href="data:image/png;base64, {!! base64_encode($qrCode) !!}" download>
  <button type="submit" class="btn btn-primary sub6">Download QR Code</button>
</a>
</div>
</div>
<!-- end qr code -->
@stop

@section('scripts')
<script>
 function printDiv() 
      {
          var divToPrint=document.getElementById('qr_code');
          var newWin=window.open('','Print-Window');
          newWin.document.open();
          newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
          newWin.document.close();
          setTimeout(function(){newWin.close();},10);
      }   

</script>
@endsection