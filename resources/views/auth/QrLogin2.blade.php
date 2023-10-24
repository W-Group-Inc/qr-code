<html>
<head>
    <title>Video Camera Example</title>
</head>
<body>
    <video id="cameraFeed" autoplay playsinline></video>
    <button id="startButton">Start Camera</button>
    <button id="stopButton" disabled>Stop Camera</button>

    <script>
        const cameraFeed = document.getElementById('cameraFeed');
        const startButton = document.getElementById('startButton');
        const stopButton = document.getElementById('stopButton');
        let stream;

        startButton.addEventListener('click', async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                cameraFeed.srcObject = stream;
                startButton.disabled = true;
                stopButton.disabled = false;
            } catch (error) {
                console.error('Error accessing the camera:', error);
            }
        });

        stopButton.addEventListener('click', () => {
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
                cameraFeed.srcObject = null;
                startButton.disabled = false;
                stopButton.disabled = true;
            }
        });
    </script>
</body>
</html>