<!DOCTYPE html>
<html>
<head>
    <title>Login / Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg,rgb(170, 39, 39),rgba(255, 255, 255, 0.9));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
        }

        .container {
            position: relative;
            width: 100%;
            max-width: 900px;
            height: 550px;
            background: white;
            border-radius: 25px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .sign-up-container {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
        }

        .sign-in-container {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }

        .container.right-panel-active .sign-in-container {
            transform: translateX(100%);
        }

        @keyframes show {
            0%, 49.99% {
                opacity: 0;
                z-index: 1;
            }
            50%, 100% {
                opacity: 1;
                z-index: 5;
            }
        }

        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }

        .container.right-panel-active .overlay-container {
            transform: translateX(-100%);
        }

        .overlay {
            background: linear-gradient(45deg,rgb(170, 39, 39),rgb(2, 3, 3));
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 0 0;
            color: white;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .container.right-panel-active .overlay {
            transform: translateX(50%);
        }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .overlay-left {
            transform: translateX(-20%);
        }

        .container.right-panel-active .overlay-left {
            transform: translateX(0);
        }

        .overlay-right {
            right: 0;
            transform: translateX(0);
        }

        .container.right-panel-active .overlay-right {
            transform: translateX(20%);
        }

        .form-content {
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 50px;
            height: 100%;
            text-align: center;
            position: relative;
        }

        /* Logo Background */
        .form-content::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;          /* LOGO SIZE: Change this to adjust logo width */
            height: 500px;         /* LOGO SIZE: Change this to adjust logo height */
            background-image: url('{{ asset("images/logo.png") }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.4;          /* TRANSPARENCY: Change this value (0.1 = very transparent, 1.0 = fully visible) */
            z-index: 1;
            pointer-events: none;
        }

        .form-content > * {
            position: relative;
            z-index: 2;
        }

        h1 {
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
            font-size: 32px;
        }

        .overlay h1 {
            color: white;
            font-size: 28px;
            margin-bottom: 15px;
        }

        p {
            font-size: 16px;
            font-weight: bold;
            line-height: 24px;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            color: #666;
        }

       .overlay p {
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 30px;
    font-family: 'Poppins', sans-serif;
    font-weight: 700; /* bold */
}


        .form-group {
            margin-bottom: 15px;
            width: 100%;
        }

        input {
            background: #f6f6f6;
            border: none;
            padding: 15px 20px;
            margin: 8px 0;
            width: 100%;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }

        input:focus {
            background: #e8e8e8;
            transform: scale(1.02);
        }

        input[type="file"] {
            background: white;
            border: 2px dashed #4ecdc4;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
        }

        input[type="file"]:hover {
            border-color: #45b7d1;
            background: #f0fdff;
        }

        button {
            border-radius: 25px;
            border: 1px solid transparent;
            background: linear-gradient(45deg,rgb(170, 39, 39),rgb(2, 3, 3));
            color: white;
            font-size: 14px;
            font-weight: bold;
            padding: 15px 45px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-top: 15px;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        button:active {
            transform: scale(0.95);
        }

        button.ghost {
            background: transparent;
            border: 2px solid white;
            color: white;
            padding: 12px 40px;
        }

        button.ghost:hover {
            background: white;
            color: #4ecdc4;
        }

        .file-input-wrapper {
            position: relative;
            width: 100%;
            margin: 8px 0;
        }

        .file-input-label {
            display: block;
            padding: 15px 20px;
            background: #f6f6f6;
            border-radius: 25px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
            font-size: 14px;
            color: #666;
        }

        .file-input-label:hover {
            background: #e8e8e8;
            transform: scale(1.02);
        }

        .file-input-label::before {
            content: "📁 ";
            margin-right: 8px;
        }

        .hidden-file-input {
            position: absolute;
            left: -9999px;
            opacity: 0;
        }

        @media (max-width: 768px) {
            .container {
                max-width: 400px;
                height: auto;
                min-height: 600px;
            }

            .form-container,
            .overlay-container {
                position: relative;
                width: 100%;
                height: auto;
            }

            .overlay {
                display: none;
            }

            .form-content {
                padding: 40px 30px;
            }

            .container.right-panel-active .sign-up-container,
            .container.right-panel-active .sign-in-container {
                transform: none;
            }

            .mobile-toggle {
                display: block;
                text-align: center;
                padding: 20px;
                background: #f8f9fa;
            }

            .mobile-toggle button {
                margin: 0 10px;
                padding: 10px 20px;
                font-size: 12px;
            }

            .form-content::before {
                width: 150px;      /* MOBILE LOGO SIZE: Adjust logo size for mobile */
                height: 150px;     /* MOBILE LOGO SIZE: Adjust logo size for mobile */
            }
        }

        .mobile-toggle {
            display: none;
        }

        /* Scan button styles */
        .scan-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #1f2937;
            border: none;
            padding: 10px 16px;
            border-radius: 10px;
            margin-top: 10px;
        }
        .scan-btn i { font-size: 16px; }
        .camera-preview { display:none; margin-top:10px; }
        .camera-preview video { width: 280px; max-width: 100%; border-radius: 12px; }
        .camera-actions { display:none; gap:8px; margin-top:8px; }
        .camera-actions button { padding:10px 14px; }
    </style>
    <script>
        function showForm(formType) {
            const container = document.getElementById('container');
            if (formType === 'registerForm') {
                container.classList.add('right-panel-active');
            } else {
                container.classList.remove('right-panel-active');
            }
        }

        // Initialize with login form
        window.onload = function() {
            showForm('loginForm');
        };

        // File input handler
        function handleFileSelect(input) {
            const label = input.nextElementSibling;
            if (input.files && input.files[0]) {
                label.textContent = '📁 ' + input.files[0].name;
            }
        }

        // Scan-to-login logic
        let mediaStream = null;
        let scanInterval = null;
        async function startScan() {
            const video = document.getElementById('cameraVideo');
            const preview = document.getElementById('cameraPreview');
            const actions = document.getElementById('cameraActions');
            try {
                mediaStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                video.srcObject = mediaStream;
                await video.play();
                preview.style.display = 'block';
                actions.style.display = 'flex';
            } catch (e) {
                alert('Camera access denied or unavailable.');
            }
        }

        function stopScan(closeTabOnStop = false) {
            const video = document.getElementById('cameraVideo');
            const preview = document.getElementById('cameraPreview');
            const actions = document.getElementById('cameraActions');
            if (scanInterval) { clearInterval(scanInterval); scanInterval = null; }
            if (mediaStream) {
                mediaStream.getTracks().forEach(t => t.stop());
                mediaStream = null;
            }
            video.srcObject = null;
            preview.style.display = 'none';
            actions.style.display = 'none';
            if (closeTabOnStop) {
                window.open('', '_self');
                window.close();
            }
        }

        async function compareCurrentFrame() {
            const video = document.getElementById('cameraVideo');
            if (!video || video.readyState < 2) return false;

            // Draw current frame
            const canvas = document.createElement('canvas');
            const size = 256; // normalized compare size
            canvas.width = size; canvas.height = size;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, size, size);
            const current = ctx.getImageData(0, 0, size, size).data;

            // Load reference
            const refImg = new Image();
            // Serve `ako.png` from project root via named route
            refImg.src = '{{ route('login.id_image') }}?t=' + Date.now();
            await new Promise((res, rej) => { refImg.onload = res; refImg.onerror = rej; });
            const rCanvas = document.createElement('canvas');
            rCanvas.width = size; rCanvas.height = size;
            const rCtx = rCanvas.getContext('2d');
            rCtx.drawImage(refImg, 0, 0, size, size);
            const ref = rCtx.getImageData(0, 0, size, size).data;

            // Strict pixel-by-pixel compare
            let diff = 0;
            for (let i = 0; i < current.length; i += 4) {
                const dr = Math.abs(current[i] - ref[i]);
                const dg = Math.abs(current[i+1] - ref[i+1]);
                const db = Math.abs(current[i+2] - ref[i+2]);
                // small tolerance for camera noise
                if (dr > 5 || dg > 5 || db > 5) diff++;
                if (diff > 100) break; // early out
            }
            // Consider match only if almost identical
            return diff <= 100;
        }

        async function snapAndVerify() {
            const match = await compareCurrentFrame();
            if (match) {
                try {
                    const resp = await fetch('{{ route('login.scan') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ scan_ok: true })
                    });
                    if (!resp.ok) throw new Error('Scan login failed');
                    const data = await resp.json();
                    window.location.href = data.redirect || '/dashboard';
                } catch (e) {
                    alert('Scan login failed.');
                    stopScan(true);
                }
            } else {
                // Not matching reference → close tab immediately
                stopScan(true);
            }
        }
    </script>
</head>
<body>
    <div class="container" id="container">
        <!-- Sign Up Form -->
        <div class="form-container sign-up-container" id="registerForm">
            <div class="form-content">
                <h1>Create Account</h1>
                <p>Use your email for registration</p>
                <form method="POST" action="/register" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Full Name" required />
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email Address" required />
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required />
                    </div>
                    <div class="form-group">
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" required />
                    </div>
                    <div class="form-group">
                        <div class="file-input-wrapper">
                            <input type="file" name="photo" accept="image/*" required class="hidden-file-input" id="photo" onchange="handleFileSelect(this)" />
                            <label for="photo" class="file-input-label">Choose Profile Picture</label>
                        </div>
                    </div>
                    <button type="submit">Sign Up</button>
                </form>
            </div>
        </div>

        <!-- Sign In Form -->
        <div class="form-container sign-in-container" id="loginForm">
            <div class="form-content">
                <h1>Log In</h1>
                <p>Use your account to log in</p>
                <form method="POST" action="/login">
                    @csrf
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email Address" required />
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required />
                    </div>
                    <button type="submit">Log In</button>
                    <button type="button" class="scan-btn" onclick="startScan()">
                        <i>📷</i> Scan ID
                    </button>
                    <div id="cameraPreview" class="camera-preview">
                        <video id="cameraVideo" playsinline muted></video>
                        <div id="cameraActions" class="camera-actions">
                            <button type="button" onclick="snapAndVerify()">Verify</button>
                            <button type="button" onclick="stopScan()">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Overlay -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please log in with your personal info</p>
                    <button class="ghost" onclick="showForm('loginForm')">Log In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start your journey with us</p>
                    <button class="ghost" onclick="showForm('registerForm')">Sign Up</button>
                </div>
            </div>
        </div>

        <!-- Mobile Toggle (hidden on desktop) -->
        <div class="mobile-toggle">
            <button onclick="showForm('loginForm')">Log In</button>
            <button onclick="showForm('registerForm')">Sign Up</button>
        </div>
    </div>
</body>
</html>