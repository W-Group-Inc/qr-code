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
        <div class="col-md-12 text-center">
            <div class="well" style="position: relative;display: inline-block;style='width:100%;';">

                <video width="300" height="320" id="qr-video" style='width:100%;'></video>
                <audio id="scanSound" preload="auto" src="{{ asset('success.mp3') }}"></audio>
                <audio id="errorSound" preload="auto" src="{{ asset('error.mp3') }}"></audio>
            </div>
        </div>
        <div class="col-md-12 text-center">
            <div class="thumbnail" id="result">
                <div class="well">
                    <div class="caption">
                        <h3>Employee Name: <span id="name-result">{{($attendances->first())->employee->name}}</span></h3>
                        <h3>Department Name: <span id="dept-result">{{($attendances->first())->employee->department}}</span></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <table class="table table-bordered" style='color:black;'>
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
function CallAjaxLoginQr(code) {
    $.ajax({
        type: "POST",
        cache: false,
        url: "{{ action('QrLoginController@checkUser') }}",
        data: { data: code },
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
        playScanSound(scanSound);
        CallAjaxLoginQr(content);
    });

    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            const selectedCamera = cameras[0]; // You can select a different camera if multiple are available.
            scanner.start(selectedCamera);

            // Check if brightness is supported
            if (selectedCamera.getCapabilities && selectedCamera.getCapabilities().brightness) {
                selectedCamera.applyConstraints({
                    advanced: [{ brightness: .2 }] // Adjust the brightness value as needed
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