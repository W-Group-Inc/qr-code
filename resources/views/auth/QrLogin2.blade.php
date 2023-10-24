<!DOCTYPE html>
<html>
<head>
    <title>QR Code Scanner</title>
</head>
<body>
    <h1>QR Code Scanner</h1>
    <video id="qr-video" width="300" height="300"></video>
    <button id="startButton">Start Camera</button>
    <button id="switchCameraButton" disabled>Switch Camera</button>
    <select id="cameraSelect" style="display: none;"></select>

    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script>
        const videoElement = document.getElementById("qr-video");
        const startButton = document.getElementById("startButton");
        const switchCameraButton = document.getElementById("switchCameraButton");
        const cameraSelect = document.getElementById("cameraSelect");
        const scanner = new Instascan.Scanner({ video: videoElement });

        // Populate the cameraSelect dropdown with available cameras
        Instascan.Camera.getCameras().then(cameras => {
            if (cameras.length > 0) {
                cameras.forEach(camera => {
                    const option = document.createElement("option");
                    option.value = camera.id;
                    option.text = camera.name;
                    cameraSelect.appendChild(option);
                });
                cameraSelect.style.display = "inline";
                switchCameraButton.disabled = false;
            } else {
                console.error("No cameras found.");
            }
        }).catch(error => {
            console.error("Error accessing cameras:", error);
        });

        startButton.addEventListener("click", () => {
            const selectedCameraId = cameraSelect.value;
            const selectedCamera = Instascan.Camera.getCameras().find(camera => camera.id === selectedCameraId);
            
            if (selectedCamera) {
                scanner.start(selectedCamera);
                startButton.disabled = true;
                switchCameraButton.disabled = false;
                cameraSelect.disabled = true;
            }
        });

        switchCameraButton.addEventListener("click", () => {
            cameraSelect.disabled = false;
            startButton.disabled = false;
            switchCameraButton.disabled = true;
            scanner.stop();
        });

        scanner.addListener("scan", function (content) {
            console.log("QR Code Scanned:", content);
        });
    </script>
</body>
</html>