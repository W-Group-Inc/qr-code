<!DOCTYPE html>
<html>
<head>
    <title>QR Code Scanner</title>
</head>
<body>
    <h1>QR Code Scanner</h1>
    <video id="qr-video" width="300" height="300"></video>

    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script>
        const videoElement = document.getElementById("qr-video");
        const scanner = new Instascan.Scanner({ video: videoElement });

        scanner.addListener("scan", function (content) {
            console.log("QR Code Scanned:", content);
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
</body>
</html>