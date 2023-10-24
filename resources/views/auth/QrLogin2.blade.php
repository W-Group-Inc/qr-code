

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
                    <canvas width="420" height="420" id="webcodecam-canvas" style='width:100%;'></canvas>
                    <div class="scanner-laser laser-rightBottom" style="opacity: 0.5;"></div>
                    <div class="scanner-laser laser-rightTop" style="opacity: 0.5;"></div>
                    <div class="scanner-laser laser-leftBottom" style="opacity: 0.5;"></div>
                    <div class="scanner-laser laser-leftTop" style="opacity: 0.5;"></div>
                </div>
                <div class="well" style="width: 100%;">
                    <button id="enableCamera" class='btn btn-danger'>Enable Camera</button> <br>
                    <label id="zoom-value" width="100">Zoom: 1</label>
                    <input id="zoom" onchange="Page.changeZoom();" type="range" min="10" max="30" value="1">
                    <label id="brightness-value"  width="100">Brightness: 0</label>
                    <input id="brightness"   onchange="Page.changeBrightness();" type="range" min="0" max="128" value="0">
                    <label id="contrast-value" style='display:none;'  width="100">Contrast: 0</label>
                    <input id="contrast"  style='display:none;' onchange="Page.changeContrast();" type="range" min="-128" max="128" value="0">
                    <label id="threshold-value"  style='display:none;' width="100">Threshold: 0</label>
                    <input id="threshold"  style='display:none;' onchange="Page.changeThreshold();" type="range" min="0" max="512" value="0">
                    <label id="sharpness-value"  style='display:none;' width="100">Sharpness: off</label>
                    <input id="sharpness"  style='display:none;' onchange="Page.changeSharpness();" type="checkbox">
                    <label id="grayscale-value"  style='display:none;' width="100">grayscale: off</label>
                    <input id="grayscale"  style='display:none;' onchange="Page.changeGrayscale();" type="checkbox">
                    <br>
                    <label id="flipVertical-value"  style='display:none;'  style='display:none;' width="100">Flip Vertical: off</label>
                    <input id="flipVertical"  style='display:none;' onchange="Page.changeVertical();" type="checkbox">
                    <label id="flipHorizontal-value"  style='display:none;' width="100">Flip Horizontal: off</label>
                    <input id="flipHorizontal"  style='display:none;' onchange="Page.changeHorizontal();" checked type="checkbox">
                </div>
                
                <select class="form-control form-control-lg" style='height:45px;' id="camera-select"></select>
                <div class="form-group">
                   
                    <button title="Decode Image" class="btn btn-default btn-sm" id="decode-img" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-upload"></span></button>
                    <button title="Image shoot" class="btn btn-info btn-sm disabled" id="grab-img" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-picture"></span></button>
                    <button title="Play" class="btn btn-success btn-sm" id="play" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-play"></span></button>
                    <button title="Pause" class="btn btn-warning btn-sm" id="pause" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-pause"></span></button>
                    <button title="Stop streams" class="btn btn-danger btn-sm" id="stop" type="button" data-toggle="tooltip"><span class="glyphicon glyphicon-stop"></span></button>
                 </div>
            </div>
            <div class="col-md-8">

                <div class="thumbnail" id="result">
                    <div class="well">
                        <img width="220" height="140" id="scanned-img" src="">
                        
                    <div class="caption">
                        <h3>Employee Name</h3>
                        <p id="scanned-QR"></p>
                        <p id="name-result"></p>
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
<script>
    const enableCameraButton = document.getElementById('enableCamera');
    const cameraFeed = document.getElementById('cameraFeed');

    enableCameraButton.addEventListener('click', async () => {
      try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });

        // Display the camera feed in a video element
        cameraFeed.srcObject = stream;
        cameraFeed.style.display = 'block';

        enableCameraButton.disabled = true;
      } catch (error) {
        console.error('Error accessing the camera:', error);
      }
    });
  </script>
<script type="text/javascript" src=" {{ URL::asset('/qr_login/option2/js/qrcodelib.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/qr_login/option2/js/webcodecamjs.js ') }}"></script>

<script>
    function askPermission(){

//add constraints object
var constraints = {
    audio:true,
    video:true};

//call getUserMedia
navigator.mediaDevices.getUserMedia(constraints).then(function(mediaStream){

   }).catch(function(err){
        console.log("There's an error!" + err.message);
    })

}
 function CallAjaxLoginQr(code) {
      $.ajax({
            type: "POST",
            cache: false,
            url : "{{action('QrLoginController@checkUser')}}",
            data: {data:code},
                success: function(data) {
                  if (data!=null) {
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

(function(undefined) {
    "use strict";

    function Q(el) {
        if (typeof el === "string") {
            var els = document.querySelectorAll(el);
            return typeof els === "undefined" ? undefined : els.length > 1 ? els : els[0];
        }
        return el;
    }
    var txt = "innerText" in HTMLElement.prototype ? "innerText" : "textContent";
    var scannerLaser = Q(".scanner-laser"),
        imageUrl = new Q("#image-url"),
        play = Q("#play"),
        scannedImg = Q("#scanned-img"),
        scannedQR = Q("#scanned-QR"),
        grabImg = Q("#grab-img"),
        decodeLocal = Q("#decode-img"),
        pause = Q("#pause"),
        stop = Q("#stop"),
        contrast = Q("#contrast"),
        contrastValue = Q("#contrast-value"),
        zoom = Q("#zoom"),
        zoomValue = Q("#zoom-value"),
        brightness = Q("#brightness"),
        brightnessValue = Q("#brightness-value"),
        threshold = Q("#threshold"),
        thresholdValue = Q("#threshold-value"),
        sharpness = Q("#sharpness"),
        sharpnessValue = Q("#sharpness-value"),
        grayscale = Q("#grayscale"),
        grayscaleValue = Q("#grayscale-value"),
        flipVertical = Q("#flipVertical"),
        flipVerticalValue = Q("#flipVertical-value"),
        flipHorizontal = Q("#flipHorizontal"),
        flipHorizontalValue = Q("#flipHorizontal-value");
    var args = {
        autoBrightnessValue: 100,
        resultFunction: function(res) {
            [].forEach.call(scannerLaser, function(el) {
                fadeOut(el, 0.5);
                setTimeout(function() {
                    fadeIn(el, 0.5);
                }, 300);
            });
            scannedImg.src = res.imgData;
            CallAjaxLoginQr(res.code);
            scannedQR[txt] = "";
        },
        getDevicesError: function(error) {
            var p, message = "Error detected with the following1 parameters:\n";
            for (p in error) {
                message += p + ": " + error[p] + "\n";
            }
            alert(message);
        },
        getUserMediaError: function(error) {
            var p, message = "Error detected with the following2 parameters:\n";
            for (p in error) {
                message += p + ": " + error[p] + "\n";
            }
            alert(message);
        },
        cameraError: function(error) {
            alert(error);
            var p, message = "Error detected with the following3 parameters:\n";
            if (error.name == "NotSupportedError") {
                var ans = confirm("Your browser does not support getUserMedia via HTTP!\n(see: https:goo.gl/Y0ZkNV).\n You want to see github demo page in a new window?");
                if (ans) {
                    window.open("http://rolandalla.com");
                }
            } else {
                for (p in error) {
                    message += p + ": " + error[p] + "\n";
                }
                alert(message);
            }
        },
        cameraSuccess: function() {
            grabImg.classList.remove("disabled");
        }
    };
    var decoder = new WebCodeCamJS("#webcodecam-canvas").buildSelectMenu("#camera-select", "environment|back").init(args);
    decodeLocal.addEventListener("click", function() {
        Page.decodeLocalImage();
    }, false);
    play.addEventListener("click", function() {
        if (!decoder.isInitialized()) {
            scannedQR[txt] = "Scanning ...";
        } else {
            scannedQR[txt] = "Scanning ...";
            decoder.play();
        }
    }, false);
    grabImg.addEventListener("click", function() {
        if (!decoder.isInitialized()) {
            return;
        }
        var src = decoder.getLastImageSrc();
        scannedImg.setAttribute("src", src);
    }, false);
    pause.addEventListener("click", function(event) {
        scannedQR[txt] = "Paused";
        decoder.pause();
    }, false);
    stop.addEventListener("click", function(event) {
        grabImg.classList.add("disabled");
        scannedQR[txt] = "Stopped";
        decoder.stop();
    }, false);
    Page.changeZoom = function(a) {
        if (decoder.isInitialized()) {
            var value = typeof a !== "undefined" ? parseFloat(a.toPrecision(1)) : zoom.value / 10;
            zoomValue[txt] = zoomValue[txt].split(":")[0] + ": " + value.toString();
            decoder.options.zoom = value;
            if (typeof a != "undefined") {
                zoom.value = a * 10;
            }
        }
    };
    Page.changeContrast = function() {
        if (decoder.isInitialized()) {
            var value = contrast.value;
            contrastValue[txt] = contrastValue[txt].split(":")[0] + ": " + value.toString();
            decoder.options.contrast = parseFloat(value);
        }
    };
    Page.changeBrightness = function() {
        if (decoder.isInitialized()) {
            var value = brightness.value;
            brightnessValue[txt] = brightnessValue[txt].split(":")[0] + ": " + value.toString();
            decoder.options.brightness = parseFloat(value);
        }
    };
    Page.changeThreshold = function() {
        if (decoder.isInitialized()) {
            var value = threshold.value;
            thresholdValue[txt] = thresholdValue[txt].split(":")[0] + ": " + value.toString();
            decoder.options.threshold = parseFloat(value);
        }
    };
    Page.changeSharpness = function() {
        if (decoder.isInitialized()) {
            var value = sharpness.checked;
            if (value) {
                sharpnessValue[txt] = sharpnessValue[txt].split(":")[0] + ": on";
                decoder.options.sharpness = [0, -1, 0, -1, 5, -1, 0, -1, 0];
            } else {
                sharpnessValue[txt] = sharpnessValue[txt].split(":")[0] + ": off";
                decoder.options.sharpness = [];
            }
        }
    };
    Page.changeVertical = function() {
        if (decoder.isInitialized()) {
            var value = flipVertical.checked;
            if (value) {
                flipVerticalValue[txt] = flipVerticalValue[txt].split(":")[0] + ": on";
                decoder.options.flipVertical = value;
            } else {
                flipVerticalValue[txt] = flipVerticalValue[txt].split(":")[0] + ": off";
                decoder.options.flipVertical = value;
            }
        }
    };
    Page.changeHorizontal = function() {
        if (decoder.isInitialized()) {
            var value = flipHorizontal.checked;
            if (value) {
                flipHorizontalValue[txt] = flipHorizontalValue[txt].split(":")[0] + ": on";
                decoder.options.flipHorizontal = value;
            } else {
                flipHorizontalValue[txt] = flipHorizontalValue[txt].split(":")[0] + ": off";
                decoder.options.flipHorizontal = value;
            }
        }
    };
    Page.changeGrayscale = function() {
        if (decoder.isInitialized()) {
            var value = grayscale.checked;
            if (value) {
                grayscaleValue[txt] = grayscaleValue[txt].split(":")[0] + ": on";
                decoder.options.grayScale = true;
            } else {
                grayscaleValue[txt] = grayscaleValue[txt].split(":")[0] + ": off";
                decoder.options.grayScale = false;
            }
        }
    };
    Page.decodeLocalImage = function() {
        if (decoder.isInitialized()) {
            decoder.decodeLocalImage(imageUrl.value);
        }
        imageUrl.value = null;
    };
    var getZomm = setInterval(function() {
        var a;
        try {
            a = decoder.getOptimalZoom();
        } catch (e) {
            a = 0;
        }
        if (!!a && a !== 0) {
            Page.changeZoom(a);
            clearInterval(getZomm);
        }
    }, 500);

    function fadeOut(el, v) {
        el.style.opacity = 1;
        (function fade() {
            if ((el.style.opacity -= 0.1) < v) {
                el.style.display = "none";
                el.classList.add("is-hidden");
            } else {
                requestAnimationFrame(fade);
            }
        })();
    }

    function fadeIn(el, v, display) {
        if (el.classList.contains("is-hidden")) {
            el.classList.remove("is-hidden");
        }
        el.style.opacity = 0;
        el.style.display = display || "block";
        (function fade() {
            var val = parseFloat(el.style.opacity);
            if (!((val += 0.1) > v)) {
                el.style.opacity = val;
                requestAnimationFrame(fade);
            }
        })();
    }
    document.querySelector("#camera-select").addEventListener("change", function() {
        if (decoder.isInitialized()) {
            decoder.stop().play();
        }
    });
}).call(window.Page = window.Page || {});

//Trigger Click 
$("document").ready(function() {
    setTimeout(function() {
        $("#play").trigger('click');
    },10);
});

window.onload = function() {
    Page.changeHorizontal();
    Page.changeZoom();
};

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