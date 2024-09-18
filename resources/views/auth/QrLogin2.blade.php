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
/* The Modal (background) */

</style>
<style>
    body {font-family: Arial, Helvetica, sans-serif;}
    
    /* The Modal (background) */
    .modal {
      display: none; /* Hidden by default */
      position: fixed; /* Stay in place */
      z-index: 1; /* Sit on top */
      padding-top: 100px; /* Location of the box */
      left: 0;
      top: 0;
      width: 100%; /* Full width */
      height: 100%; /* Full height */
      overflow: auto; /* Enable scroll if needed */
      background-color: rgb(0,0,0); /* Fallback color */
      background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }
    
    /* Modal Content */
    .modal-content {
      background-color: #fefefe;
      margin: auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
    }
    
    /* The Close Button */
    .close {
      color: #aaaaaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }
    
    .close:hover,
    .close:focus {
      color: #000;
      text-decoration: none;
      cursor: pointer;
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
        <div class="col-md-6 text-left">
            <div class="thumbnail" id="result">
                <div class="well">
                    <div class="caption">
                        
                        <h6>Last Employee</h6> <br> <h3><span id="name-result">@if($attendances->first()){{($attendances->first())->employee->name}}@endif</span></h3>
                        <h3><span id="dept-result">@if($attendances->first()){{($attendances->first())->employee->department}}@endif</span></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <table class="table table-bordered" style='color:white;'>
                <thead style='font-color:white;'>
                <tr style='color:white;'>
                    <td style='color:white;'>Name</td>
                    <td style='color:white;' scope="col">Department</td>
                    <td style='color:white;' scope="col">Break Out</td>
                    <td style='color:white;' scope="col">Break In</td>
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
<input name='code' value='' id='code' type='hidden'>
<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <div class='row mt-4'>
            <div class='col-md-4'> <button type="button" id='am_break' value="AM Break" class="btn btn-primary btn-lg btn-block">AM Break</button></div>
            <div class='col-md-4'> <button type="button" id='pm_break' value="PM Break" class="btn btn-primary btn-lg btn-block">PM Break</button></div>
            <div class='col-md-4'> <button type="button"  id='lunch_break' value="Lunch Break" class="btn btn-primary btn-lg btn-block">Lunch Break </button></div>
        </div>
        <div class='row mt-4'>
            <div class='col-md-4'> <button type="button" value="Pick Up (Order)"  class="btn btn-primary btn-lg btn-block">Pick Up (Order) </button></div>
            <div class='col-md-4'> <button type="button" value="OB going to bank" class="btn btn-primary btn-lg btn-block">OB going to bank </button></div>
            <div class='col-md-4'> <button type="button" value="OB Field work" class="btn btn-primary btn-lg btn-block">OB Field work</button></div>
        </div>
        <div class='row mt-4'>
            <div class='col-md-4'> <button type="button" value="OB go to other bldgs" class="btn btn-primary btn-lg btn-block">OB go to other bldgs</button></div>
            <div class='col-md-4'> <button type="button" value="OB go to the plant" class="btn btn-primary btn-lg btn-block">OB go to the plant</button></div>
            <div class='col-md-4'> <button type="button" value="Others" class="btn btn-danger btn-lg btn-block">Others</button></div>
        </div>
    </div>
  
  </div>
@endsection

@if( !Sentinel::getUser())
@section('scripts')
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

<script type="text/javascript">
 $("button").click(function() {
    var fired_button = $(this).val();
    var code = document.getElementById("code").value;
    // alert(fired_button);
    post_action(code,fired_button);
    var modal = document.getElementById("myModal");
    modal.style.display = "none";
    show_message();

});
    </script>
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
    var time_now = document.getElementById('time').textContent;
    
    var timeToCheck = time_now;
    var startTime_am_break = '07:00:00';
    var endTime_am_break = '09:00:00';
    var startTime_pm_break = '15:00:00';
    var endTime_pm_break = '17:00:00';
    var startTime_lunch_break = '12:00:00';
    var endTime_lunch_break = '13:00:00';

    // Convert time strings to Date objects for comparison
    var timeToCheckDate = new Date('1970-01-01T' + timeToCheck + 'Z');
    var startTimeDate = new Date('1970-01-01T' + startTime_am_break + 'Z');
    var endTimeDate = new Date('1970-01-01T' + endTime_am_break + 'Z');
    var startTimeDatepmbreak = new Date('1970-01-01T' + startTime_pm_break + 'Z');
    var endTimeDatepmbreak = new Date('1970-01-01T' + endTime_pm_break + 'Z');
    var startTimeDatelunch_break = new Date('1970-01-01T' + startTime_lunch_break + 'Z');
    var endTimeDatelunch_break = new Date('1970-01-01T' + endTime_lunch_break + 'Z');

    // Check if timeToCheck is between startTime and endTime
    if (timeToCheckDate >= startTimeDate && timeToCheckDate <= endTimeDate) {
        document.getElementById("am_break").disabled = false;
    } else {
        document.getElementById("am_break").disabled = true;
    }
    if (timeToCheckDate >= startTimeDatepmbreak && timeToCheckDate <= endTimeDatepmbreak) {
        document.getElementById("pm_break").disabled = false;
    } else {
        document.getElementById("pm_break").disabled = true;
    }
    if (timeToCheckDate >= startTimeDatelunch_break && timeToCheckDate <= endTimeDatelunch_break) {
        document.getElementById("lunch_break").disabled = false;
    } else {
        document.getElementById("lunch_break").disabled = true;
    }
    // if(document.getElementById('time').textContent >)
    var modal = document.getElementById("myModal");
    modal.style.display = "block";
   
}
function post_action(code,fired_button)
{
    $.ajax({
        type: "POST",
        cache: false,
        url: "{{ action('QrLoginController@checkUser') }}",
        data: { data: code ,id :location_id ,reason:fired_button},
        success: function (data) {
            if (data.user != null) {
                console.log(data.attendance);
                document.getElementById("name-result").innerHTML = data.user.name;
                document.getElementById("dept-result").innerHTML = data.user.department;
                if (data.attendance.length == 0) {
                } else {
                    if (data.attendance.break_in) {
                        $('<tr><td>' + data.user.name + '</td><td>' + data.user.department + '</td><td>' + data.attendance.break_out + '</td><td>' + data.attendance.break_in + '</td></tr>').insertBefore('table > tbody > tr:first');
                    } else {
                        $('<tr><td>' + data.user.name + '</td><td>' + data.user.department + '</td><td>' + data.attendance.break_out + '</td><td></td></tr>').insertBefore('table > tbody > tr:first');
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
        document.getElementById("code").value = content;
        playScanSound(scanSound);
        CallAjaxLoginQr(content);
        // show_message();
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
        span.textContent = ("0" + h).substr(-2) + ":" + ("0" + m).substr(-2) + ":" + ("0" + s).substr(-2);
    }

    setInterval(time, 1000);
</script>
@endsection
@endif