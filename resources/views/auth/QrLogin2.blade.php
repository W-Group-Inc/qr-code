@extends('frontLayout.app')
@section('title')
QR Login
@stop

@section('style')
<style>
 .success-message {
  background-color: #4CAF50;
  color: white;
  text-align: center;
  padding: 10px;
  position: fixed;
  top: 100px;
  left: 50%;
  transform: translateX(-50%);
  border-radius: 5px;
  display: none;
  z-index: 1000;
}
/* Style the video container with a border inside */
.video {
    width: 100%;
    position: relative;
    overflow: hidden;
}

/* Add a border inside the video container */
#qr-video {
    width: 100%;
    height: auto;
      padding: -20px;
    border: 4px solid #000; /* Set the border color and size */
    box-sizing: border-box; /* Make the border stay inside the video */
}

/* Create a scanner line */
#qr-line {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px; /* Adjust the height as needed for the scanning line */
    background: #00ff00; /* Set the color of the scanning line */
    animation: scan 3s linear infinite; /* Add a scanning animation */
}

/* Keyframe animation for the scanning line */
@keyframes scan {
    0% {
        transform: translate(0, 0); /* Start position of the line */
    }
    100% {
        transform: translate(0, 100%); /* End position of the line */
    }
}
</style>

@stop

@section('content')
<div class="container" id="QR-Code">
    <div class='row'>
        <div class="col-md-12 text-center">
            <h1>{{$location->location}}</h1>
        </div>
        <div class='col-md-12 text-center'>
            <h1 style='font-size: 100px;' id='time'></h1>
        </div>
    </div>
    <div class='row'>
        <div id="successMessage" style="display: none;" class="success-message">
        Success! Qr Code Scanned.
        </div>
       
        <div class="col-md-6 text-center">
            <div class="well" style="position: relative;display: inline-block;style='width:100%;';">
                <div class='video'> 
                <video class='video' width="300" height="300" id="qr-video" ></video>
                </div>
                <audio style='opacity:.1;' controls   autoplay  id="scanSound" preload="auto" src="{{ asset('success.mp3') }}"></audio>
            </div>
        </div>
        <div class="col-md-6 text-center">
            <table class="table table-bordered"  border='1' style='color:black;width:100%;' >
                <tr style='color:black;'>
                    <th><h3>Employee Name</h3></th>
                </tr>
                <tr style='color:black;'>
                    <td><h3><span id="name-result">@if($attendances->first()){{($attendances->first())->employee->name}}@endif</span></h3></td>
                </tr>
                <tr style='color:black;'>
                    <th><h3>Department Name</h3></th>
                </tr>
                <tr style='color:black;'>
                    <td><h3> <span id="dept-result">@if($attendances->first()){{($attendances->first())->employee->department}}@endif</span></h3></td>
                </tr>
            </table>
        </div>
        <div class="col-md-12">
            <table class="table table-bordered" id='attendan' style='color:black;'>
                <thead style='font-color:black;'>
                <tr style='color:black;'>
                    <td style='color:black;'>Name</td>
                    <td style='color:black;' scope="col">Department</td>
                    <td style='color:black;' scope="col">Break Out</td>
                    <td style='color:black;' scope="col">Break In</td>
                </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                    <tr>
                        <td>{{$attendance->employee->name}}</td>
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
var location_id = {!! json_encode($location->id) !!};
function show_message()
{
    var successMessage = document.getElementById("successMessage");

// Display the success message
successMessage.style.display = "block";

// Hide the message after a certain duration (e.g., 3 seconds)
setTimeout(function () {
  successMessage.style.display = "none";
}, 2000); // 3000 milliseconds (3 seconds)
    
}
function CallAjaxLoginQr(code) {
    console.log(code);
    $.ajax({
        type: "POST",
        cache: false,
        url: "{{ action('QrLoginController@checkUser') }}",
        data: { data: code ,id :location_id },
        success: function (data) {
            if (data.user != null) {
                console.log(data.attendance);
                document.getElementById("name-result").innerHTML = data.user.name;
                document.getElementById("dept-result").innerHTML = data.user.department;
                if (data.attendance.length == 0) {
                } else {
                    if (data.attendance.break_in) {
                        $('<tr><td>' + data.user.name + '</td><td>' + data.user.department + '</td><td>' + data.attendance.break_out + '</td><td>' + data.attendance.break_in + '</td></tr>').insertBefore('#attendan table > tbody > tr:first');
                    } else {
                        $('<tr><td>' + data.user.name + '</td><td>' + data.user.department + '</td><td>' + data.attendance.break_out + '</td><td></td></tr>').insertBefore('#attendan table > tbody > tr:first');
                    }
                }
            } else {
                return confirm('There is no user with this QR code');
            }
        }
    });
}
function playScanSound() {
        const scanSound = document.getElementById("scanSound");
        if (scanSound) {
            scanSound.play();
        }
    }
</script>
<script>
    const videoElement = document.getElementById("qr-video");
    const scanner = new Instascan.Scanner({ video: videoElement });

    scanner.addListener("scan", function (content) {
        playScanSound(scanSound);
        CallAjaxLoginQr(content);
        show_message();
    });

    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            const selectedCamera = cameras[0]; // You can select a different camera if multiple are available.
            scanner.start(selectedCamera);

            // Check if brightness is supported
            if (selectedCamera.getCapabilities && selectedCamera.getCapabilities().brightness) {
                selectedCamera.applyConstraints({
                    advanced: [{ brightness: 1 }] // Adjust the brightness value as needed
                }).catch(function (error) {
                    console.error("Error adjusting brightness:", error);
                });
            }
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