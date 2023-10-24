

@extends('frontLayout.app')
@section('title')
QR Login

@stop
@section('style')
@stop
@section('content')
<div class="container" id="QR-Code">   
    <div class='row'>
        <div class='col-md-12 text-center'>
            <h1 id='time'></h1>
        </div>
    </div>
    <div class='row'>         
            <div class="col-md-4">
                <div class="well" style="position: relative;display: inline-block;style='width:100%';">
                    
                    <video width="420" height="420" id="qr-video" style='width:100%;'></video>
                </div>
            </div>
            <div class="col-md-8">

                <div class="thumbnail" id="result">
                    <div class="well">
                    <div class="caption">
                        <h3>Employee Name:   <span id="name-result"></span></h3>
                      
                    </div>
                    </div>
                </div>
                <table class="table table-bordered ">
                    <thead>
                      <tr>
                        <td scope="col">Name</td>
                        <td scope="col">Department</td>
                        <td scope="col">Break Out</td>
                        <td scope="col">Break In</td>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                        <tr>
                            <td >{{$attendance->employee->name}}</td>
                            <td>{{$attendance->employee->department}}</td>
                            <td>{{$attendance->break_out}}</td>
                            <td>{{$attendance->break_in}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                  </table>
                
            </div>
    </div>
 </div>
@endsection
@if( !Sentinel::getUser())
@section('scripts')
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script>
   
function CallAjaxLoginQr(code) {
      $.ajax({
            type: "POST",
            cache: false,
            url : "{{action('QrLoginController@checkUser')}}",
            data: {data:code},
                success: function(data) {
                  if (data.user != null) {
                    console.log(data.attendance);
                    document.getElementById("name-result").innerHTML = data.user.name;
                    if((data.attendance).length == 0)
                    {
                    }
                    else
                    {
                        if(data.attendance.break_in)
                        {
                            $('<tr><td>'+data.user.name+'</td><td>'+data.user.department+'</td><td>'+data.attendance.break_out+'</td><td>'+data.attendance.break_in+'</td></tr>').insertBefore('table > tbody > tr:first');
                  
                        }
                        else
                        {
                            $('<tr><td>'+data.user.name+'</td><td>'+data.user.department+'</td><td>'+data.attendance.break_out+'</td><td></td></tr>').insertBefore('table > tbody > tr:first');
                  
                        }
                    }
                   
                    // location.reload();
                  }else{
                   return confirm('There is no user with this qr code'); 
                  }
                  // 
                }
            })
 }

</script>
<script>
    const videoElement = document.getElementById("qr-video");
    const scanner = new Instascan.Scanner({ video: videoElement });

    scanner.addListener("scan", function (content) {
        CallAjaxLoginQr(content);
    });

    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            const selectedCamera = cameras[0]; // You can select a different camera if multiple are available.
            scanner.start(selectedCamera);
        } else {
            console.error("No cameras found.");
        }
    }).catch(function (error) {
        console.error("Error accessing camera:", error);
    });
</script>
<script>
    var span = document.getElementById('time');
  
    function time() {
    var d = new Date();
    var s = d.getSeconds();
    var m = d.getMinutes();
    var h = d.getHours();
    span.textContent = 
        ("0" + h).substr(-2) + ":" + ("0" + m).substr(-2) + ":" + ("0" + s).substr(-2);
    }
  
    setInterval(time, 1000);
  </script>

@endsection
@endif