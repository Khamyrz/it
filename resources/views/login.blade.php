<!DOCTYPE html>
<html>
<head>
    <title>Login / Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
    <meta name="password-hash" content="argon2id">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            font-size: 16px; /* ensure readable */
            line-height: 1.2; /* ensure text fits */
            color: #222; /* strong contrast */
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

        button, .btn-like {
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

        button:hover, .btn-like:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        button:active, .btn-like:active {
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

        /* Password Reset Modal */
        #passwordResetModal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            z-index: 10000;
            align-items: center;
            justify-content: center;
        }

        .reset-modal-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            position: relative;
        }

        .reset-step {
            display: none;
        }

        .reset-step.active {
            display: block;
        }

        .reset-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .reset-header h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }

        .reset-header p {
            color: #666;
            font-size: 14px;
        }

        .otp-input {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 20px 0;
            flex-wrap: nowrap; /* ensure 6 boxes stay in one row */
            width: 100%;
        }

        .otp-input input {
            width: 54px;
            height: 54px;
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            border: 3px solid #007bff;
            border-radius: 12px;
            background: white;
            color: #000;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            line-height: 1;
            padding: 0;
            margin: 0 5px;
        }

        .otp-input input:focus {
            border-color: #0056b3;
            background: #f8f9fa;
            box-shadow: 0 0 0 4px rgba(0,123,255,0.3);
            outline: none;
            transform: scale(1.05);
        }

        .otp-input input:not(:placeholder-shown) {
            background: #e3f2fd;
            border-color: #1976d2;
            color: #000;
            font-weight: 900;
        }

        .otp-input input::placeholder {
            color: #ccc;
            font-size: 20px;
        }

        .otp-timer {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin: 10px 0;
        }

        .otp-timer.warning {
            color: #dc3545;
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }

        .modal-close:hover {
            color: #333;
        }

        .resend-btn {
            background: none;
            border: none;
            color: #007bff;
            cursor: pointer;
            text-decoration: underline;
            font-size: 14px;
        }

        .resend-btn:disabled {
            color: #999;
            cursor: not-allowed;
        }
        
        /* Keep 6 boxes in a single row on smaller screens */
        @media (max-width: 420px) {
            .otp-input { gap: 6px; }
            .otp-input input {
                width: 40px;
                height: 48px;
                font-size: 20px;
                margin: 0;
            }
        }
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

        // Secure file input handler (reject PHP and non-images)
        function handleFileSelect(input) {
            const label = input.nextElementSibling;
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileName = (file.name || '').toLowerCase();
                const ext = fileName.split('.').pop();
                const forbidden = ['php','php3','php4','php5','php7','pht','phtml','phar'];
                const isImage = (file.type || '').startsWith('image/');
                if (!isImage || forbidden.includes(ext)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Only image files are allowed.',
                        confirmButtonColor: '#dc3545',
                        zIndex: 10001
                    });
                    input.value = '';
                    if (label && label.classList.contains('file-input-label')) {
                        label.textContent = 'Choose Profile Picture';
                    }
                    return;
                }
                const safeName = file.name.replace(/[<>"'&]/g, '');
                if (label && label.classList.contains('file-input-label')) {
                    label.textContent = '📁 ' + safeName;
                }
            }
        }

        // Client-side lockout (10 minutes) to mitigate brute-force; backend enforcement recommended
        (function(){
            const LOCK_KEY = 'auth_lockout_until';
            const ATTEMPTS_KEY = 'auth_attempts';
            const WINDOW_KEY = 'auth_window_start';
            const MAX_ATTEMPTS = 5;
            const WINDOW_MS = 10 * 60 * 1000; // 10 minutes
            const LOCKOUT_MS = 10 * 60 * 1000; // 10 minutes

            function now(){ return Date.now(); }
            function getNum(key){ return parseInt(localStorage.getItem(key) || '0', 10); }
            function setNum(key, val){ localStorage.setItem(key, String(val)); }
            function getTs(key){ const v = parseInt(localStorage.getItem(key) || '0', 10); return isNaN(v)?0:v; }
            function setTs(key, val){ localStorage.setItem(key, String(val)); }

            function isLocked(){ return getTs(LOCK_KEY) > now(); }

            function disableAllButtons(){
                document.querySelectorAll('button').forEach(b=>{ b.disabled = true; b.dataset._disabled='1'; });
                // Show lockout message using SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Account Locked',
                    text: 'Too many login attempts. Your account has been temporarily locked.',
                    confirmButtonColor: '#dc3545',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    zIndex: 10001,
                    didOpen: () => {
                        // Start the countdown timer
                        startLockoutTimer();
                    }
                });
            }

            function enableAllButtons(){
                document.querySelectorAll('button').forEach(b=>{ if(b.dataset._disabled==='1'){ b.disabled = false; delete b.dataset._disabled; } });
                // Close any open SweetAlert
                Swal.close();
            }

            let timerInterval = null;
            function startTimer(){
                function tick(){
                    const rem = Math.max(0, getTs(LOCK_KEY) - now());
                    const m = String(Math.floor(rem/60000)).padStart(2,'0');
                    const s = String(Math.floor((rem%60000)/1000)).padStart(2,'0');
                    
                    // Update SweetAlert content with countdown
                    if(Swal.isVisible()){
                        Swal.update({
                            html: `
                                <div style="text-align: center;">
                                    <h3 style="color: #dc3545; margin-bottom: 15px;">Account Locked</h3>
                                    <p style="color: #666; margin-bottom: 10px;">Too many login attempts. Your account has been temporarily locked.</p>
                                    <p style="color: #dc3545; font-weight: bold; font-size: 18px;">Try again in: <span id="lockout-timer">${m}:${s}</span></p>
                                </div>
                            `
                        });
                    }
                    
                    if(rem<=0){
                        clearInterval(timerInterval); timerInterval=null;
                        localStorage.removeItem(LOCK_KEY);
                        localStorage.removeItem(ATTEMPTS_KEY);
                        localStorage.removeItem(WINDOW_KEY);
                        enableAllButtons();
                    }
                }
                if(timerInterval) clearInterval(timerInterval);
                timerInterval = setInterval(tick, 500);
                tick();
            }

            function startLockoutTimer(){
                startTimer();
            }

            function ensureWindow(){
                const start = getTs(WINDOW_KEY);
                const n = now();
                if(!start || (n - start) > WINDOW_MS){ setTs(WINDOW_KEY, n); setNum(ATTEMPTS_KEY, 0); }
            }

            function recordAttempt(){
                ensureWindow();
                const attempts = getNum(ATTEMPTS_KEY) + 1;
                setNum(ATTEMPTS_KEY, attempts);
                if(attempts >= MAX_ATTEMPTS){ setTs(LOCK_KEY, now() + LOCKOUT_MS); }
            }

            function guardSubmit(e){
                if(isLocked()){
                    e.preventDefault(); e.stopPropagation();
                    disableAllButtons(); startTimer(); return false;
                }
                recordAttempt();
                return true;
            }

            function init(){
                const loginForm = document.querySelector('form[action="/login"]');
                if(loginForm){
                    // Captcha gate before submitting to backend
                    let captchaPassed = false;
                    loginForm.addEventListener('submit', function(e){
                        if (!captchaPassed) {
                            e.preventDefault(); e.stopPropagation();
                            openCaptchaModal(function onSolved(){
                                captchaPassed = true;
                                // add hidden field to signal captcha ok (optional server-side check)
                                let h = loginForm.querySelector('input[name="captcha_ok"]');
                                if(!h){ h = document.createElement('input'); h.type='hidden'; h.name='captcha_ok'; loginForm.appendChild(h); }
                                h.value = '1';
                                loginForm.submit();
                            });
                            return false;
                        }
                        return true;
                    }, {capture:true});
                    // still guard for lockout
                    loginForm.addEventListener('submit', guardSubmit, {capture:true});
                }
                if(isLocked()){ disableAllButtons(); startTimer(); }
            }
            document.addEventListener('DOMContentLoaded', init);
        })();
    </script>
    <script>
        // Super Admin Modal Logic + Keyboard Shortcut (Ctrl+Alt+Shift+S)
        (function(){
            let saOtpTimer = null;
            let saToken = '';
            let saAttemptsRemaining = 2; // two retries
            const SA_LOCK_KEY = 'sa_lock_until'; // 24h lockout

            function now(){ return Date.now(); }
            function getTs(k){ const v = parseInt(localStorage.getItem(k) || '0', 10); return isNaN(v)?0:v; }
            function setTs(k,v){ localStorage.setItem(k, String(v)); }

            window.openSuperAdminModal = function(){
                if(getTs(SA_LOCK_KEY) > now()){
                    const rem = getTs(SA_LOCK_KEY) - now();
                    const hrs = Math.ceil(rem/3600000);
                    Swal.fire({ icon:'error', title:'Locked', text:`Super Admin login locked. Try again in about ${hrs} hour(s).`, confirmButtonColor:'#dc3545', zIndex:10001 });
                    return;
                }
                const m = document.getElementById('superAdminModal'); if(!m) return;
                m.style.display = 'flex';
                resetSaToStep(1);
                const email = document.getElementById('saEmail'); if(email) email.focus();
            };

            window.closeSuperAdminModal = function(){
                const m = document.getElementById('superAdminModal'); if(!m) return;
                m.style.display = 'none';
                resetSaToStep(1);
                clearSaTimer();
                // clear fields
                const f1 = document.getElementById('saLoginForm'); if(f1) f1.reset();
                document.querySelectorAll('#superAdminModal .sa-otp-digit').forEach(i=>i.value='');
            };

            function resetSaToStep(step){
                document.querySelectorAll('#superAdminModal .sa-step').forEach(s=>s.classList.remove('active'));
                document.getElementById(step===1?'saStep1':'saStep2').classList.add('active');
            }

            function clearSaTimer(){ if(saOtpTimer){ clearInterval(saOtpTimer); saOtpTimer=null; } }
            function startSaTimer(){
                let t = 60; const timerEl = document.getElementById('saOtpTimer'); const countEl = document.getElementById('saTimerCount'); const btn = document.getElementById('saResendBtn');
                clearSaTimer(); if(btn) btn.disabled = true; if(timerEl) timerEl.classList.remove('warning');
                saOtpTimer = setInterval(()=>{
                    t--; if(countEl) countEl.textContent = t;
                    if(t<=10 && timerEl) timerEl.classList.add('warning');
                    if(t<=0){
                        clearSaTimer();
                        if(btn) btn.disabled = false;
                        if(timerEl){ timerEl.textContent = 'OTP expired. Click resend to get a new code.'; timerEl.classList.remove('warning'); }
                    }
                },1000);
            }

            async function postJSON(url, payload){
                const res = await fetch(url, { method:'POST', headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept':'application/json' }, credentials:'same-origin', body: JSON.stringify(payload) });
                let data={}; try{ data=await res.json(); }catch(e){}
                return { ok: res.ok, status: res.status, data };
            }

            window.superAdminLogin = async function(){
                if(getTs(SA_LOCK_KEY) > now()) return openSuperAdminModal();
                const email = (document.getElementById('saEmail')?.value||'').trim();
                const password = document.getElementById('saPassword')?.value||'';
                if(!email || !password){
                    Swal.fire({ icon:'warning', title:'Missing Fields', text:'Enter email and password', confirmButtonColor:'#ffc107', zIndex:10001 });
                    return;
                }
                // Immediately show OTP step for faster UX while the server sends the email
                saAttemptsRemaining = 2;
                const emailSpan = document.getElementById('saOtpEmail'); if(emailSpan) emailSpan.textContent = email;
                resetSaToStep(2);
                startSaTimer();
                const first = document.querySelector('#superAdminModal .sa-otp-digit'); if(first) first.focus();
                // Show subtle info
                Swal.fire({ icon:'info', title:'Check Your Email', text:'We are sending your OTP...', timer:1200, showConfirmButton:false, zIndex:10001 });
                // Send request in background
                const r = await postJSON('/super-admin/login', { email, password });
                if(r.ok){
                    saToken = r.data.token;
                    // stay on OTP step
                } else {
                    // revert to login step on error
                    resetSaToStep(1);
                    Swal.fire({ icon:'error', title:'Login Failed', text: r.data?.message || 'Invalid credentials or super-admin already exists', confirmButtonColor:'#dc3545', zIndex:10001 });
                }
            };

            window.resendSuperAdminOTP = async function(){
                const email = document.getElementById('saOtpEmail')?.textContent||'';
                const r = await postJSON('/super-admin/resend-otp', { token: saToken, email });
                if(r.ok){ startSaTimer(); if(r.data && r.data.debug_otp){ const hint = document.getElementById('saOtpDevHint'); if(hint){ hint.textContent='Development Mode - OTP: '+r.data.debug_otp; hint.style.display='block'; } } }
                else { Swal.fire({ icon:'error', title:'Failed', text:r.data?.message||'Failed to resend', confirmButtonColor:'#dc3545', zIndex:10001 }); }
            };

            window.verifySuperAdminOTP = async function(){
                const code = Array.from(document.querySelectorAll('#superAdminModal .sa-otp-digit')).map(i=>i.value).join('');
                if(code.length!==6){
                    Swal.fire({ icon:'warning', title:'Incomplete OTP', text:'Enter 6-digit OTP', confirmButtonColor:'#ffc107', zIndex:10001 });
                    return;
                }
                const r = await postJSON('/super-admin/verify-otp', { token: saToken, otp: code });
                if(r.ok){
                    window.location.href = '/admin-dashboard';
                } else {
                    saAttemptsRemaining = (r.data && r.data.remaining_attempts!==undefined) ? r.data.remaining_attempts : (saAttemptsRemaining-1);
                    if(saAttemptsRemaining <= 0){
                        setTs(SA_LOCK_KEY, now() + 24*60*60*1000);
                        closeSuperAdminModal();
                        Swal.fire({ icon:'error', title:'Locked', text:'Too many invalid OTP attempts. Super Admin login locked for 24 hours.', confirmButtonColor:'#dc3545', zIndex:10001 });
                        return;
                    }
                    Swal.fire({ icon:'error', title:'Invalid OTP', text:`Try again. Attempts left: ${saAttemptsRemaining}`, confirmButtonColor:'#dc3545', zIndex:10001 }).then(()=>{
                        document.querySelectorAll('#superAdminModal .sa-otp-digit').forEach(i=>i.value='');
                        const first = document.querySelector('#superAdminModal .sa-otp-digit'); if(first) first.focus();
                    });
                }
            };

            // Shortcut handler
            document.addEventListener('keydown', function(e){
                if(e.ctrlKey && e.altKey && e.shiftKey && (e.key==='S' || e.key==='s')){
                    e.preventDefault();
                    openSuperAdminModal();
                }
            });

            // OTP navigation for SA
            document.addEventListener('DOMContentLoaded', function(){
                const inputs = document.querySelectorAll('#superAdminModal .sa-otp-digit');
                inputs.forEach((input, idx)=>{
                    input.addEventListener('input', function(){ if(this.value && idx<inputs.length-1){ inputs[idx+1].focus(); } });
                    input.addEventListener('keydown', function(e){ if(e.key==='Backspace' && !this.value && idx>0){ inputs[idx-1].focus(); } });
                });

                // Check status: if already registered, keep register option; else allow registration
                fetch('/super-admin/status', { headers: { 'Accept':'application/json' }})
                    .then(r=>r.json()).then(s=>{
                        // Optional: you can hide the register button if already registered
                        if(s && s.registered){ /* already registered */ }
                    }).catch(()=>{});
            });

            // Registration modal controls
            window.openSuperAdminRegister = function(){
                fetch('/super-admin/status', { headers: { 'Accept':'application/json' }})
                    .then(r=>r.json()).then(s=>{
                        if(s && s.registered){
                            Swal.fire({ icon:'info', title:'Already Registered', text:'A Super Admin is already registered.', confirmButtonColor:'#17a2b8', zIndex:10001 });
                        } else {
                            const m = document.getElementById('superAdminRegisterModal'); if(m){ m.style.display='flex'; document.getElementById('sarEmail').focus(); }
                        }
                    }).catch(()=>{
                        const m = document.getElementById('superAdminRegisterModal'); if(m){ m.style.display='flex'; document.getElementById('sarEmail').focus(); }
                    });
            };
            window.closeSuperAdminRegister = function(){ const m=document.getElementById('superAdminRegisterModal'); if(m){ m.style.display='none'; const f=document.getElementById('saRegisterForm'); if(f) f.reset(); } };

            window.registerSuperAdmin = async function(){
                const email = (document.getElementById('sarEmail')?.value||'').trim();
                const p1 = document.getElementById('sarPassword')?.value||'';
                const p2 = document.getElementById('sarPassword2')?.value||'';
                if(!email || !p1 || !p2){
                    Swal.fire({ icon:'warning', title:'Missing Fields', text:'Fill all fields', confirmButtonColor:'#ffc107', zIndex:10001 }); return;
                }
                if(p1.length < 6){ Swal.fire({ icon:'warning', title:'Weak Password', text:'Minimum 6 characters', confirmButtonColor:'#ffc107', zIndex:10001 }); return; }
                if(p1 !== p2){ Swal.fire({ icon:'error', title:'Password Mismatch', text:'Passwords do not match', confirmButtonColor:'#dc3545', zIndex:10001 }); return; }
                try{
                    const res = await fetch('/super-admin/register', { method:'POST', headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept':'application/json' }, credentials:'same-origin', body: JSON.stringify({ email, password: p1 }) });
                    const data = await res.json().catch(()=>({}));
                    if(res.ok){
                        closeSuperAdminRegister();
                        Swal.fire({ icon:'success', title:'Registered', text:'Super Admin registered. You can now log in.', confirmButtonColor:'#28a745', zIndex:10001 });
                        // prefill login email
                        const e = document.getElementById('saEmail'); if(e){ e.value = email; }
                    } else if(res.status===409){
                        closeSuperAdminRegister();
                        Swal.fire({ icon:'info', title:'Already Registered', text:data.message||'Super Admin already registered', confirmButtonColor:'#17a2b8', zIndex:10001 });
                    } else {
                        Swal.fire({ icon:'error', title:'Failed', text:data.message||'Registration failed', confirmButtonColor:'#dc3545', zIndex:10001 });
                    }
                }catch(e){
                    Swal.fire({ icon:'error', title:'Network Error', text:'Please try again.', confirmButtonColor:'#dc3545', zIndex:10001 });
                }
            };
        })();
    </script>
    <style>
        /* Super Admin Modal */
        #superAdminModal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            z-index: 10050;
            align-items: center;
            justify-content: center;
        }

        .sa-modal-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            width: 90%;
            max-width: 520px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            position: relative;
        }

        .sa-header { text-align:center; margin-bottom: 20px; }
        .sa-header h3 { margin: 0 0 6px 0; font-size: 22px; color:#222; }
        .sa-header p { margin: 0; color:#666; font-size: 14px; }

        .sa-step { display:none; }
        .sa-step.active { display:block; }

        .sa-hint { text-align:center; color:#c00; font-weight:bold; margin-top:8px; display:none; }
        .sa-timer { text-align:center; color:#666; font-size: 14px; margin-top:8px; }
        .sa-timer.warning { color:#dc3545; }
    </style>
</head>
<body>
    <!-- Session Flash Messages -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#28a745',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    zIndex: 10001
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#dc3545',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    zIndex: 10001
                });
            });
        </script>
    @endif

    @if(session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: '{{ session('warning') }}',
                    confirmButtonColor: '#ffc107',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    zIndex: 10001
                });
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let errorMessage = '';
                @foreach($errors->all() as $error)
                    errorMessage += '{{ $error }}\n';
                @endforeach
                
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error!',
                    text: errorMessage.trim(),
                    confirmButtonColor: '#dc3545',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    zIndex: 10001
                });
            });
        </script>
    @endif

    <div class="container" id="container">
        <!-- Sign Up Form -->
        <div class="form-container sign-up-container" id="registerForm">
            <div class="form-content">
                <h1>Create Account</h1>
                <p>Use your Gmail account for registration</p>
                <div id="emailVerificationStatus" style="display:none; background:#fff3cd; border:1px solid #ffeaa7; color:#856404; padding:10px; border-radius:5px; margin-bottom:15px; font-size:14px;">
                    <strong>⚠️ Email Verification Required:</strong> Please verify your Gmail address to continue registration.
                </div>
                <form method="POST" action="/register" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Full Name" required />
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Gmail Address (e.g., user@gmail.com)" required pattern="[a-zA-Z0-9._%+-]+@gmail\.com$" title="Please enter a valid Gmail address" />
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
                <p>Use your approved Gmail account to access the IT Inventory System</p>
                <form method="POST" action="/login">
                    @csrf
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Gmail Address (e.g., user@gmail.com)" required pattern="[a-zA-Z0-9._%+-]+@gmail\.com$" title="Please enter a valid Gmail address" />
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required />
                    </div>
                    <div style="display:flex; gap:12px; align-items:center; justify-content:center; flex-wrap:wrap;">
                        <button type="submit">Log In</button>
                        <button type="button" onclick="openPasswordResetModal()" class="btn-like" style="display:inline-block; text-decoration:none;">Forgot Password?</button>
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

    <!-- Email Verification Modal for Registration -->
    <div id="emailVerificationModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:10000; align-items:center; justify-content:center;">
        <div class="reset-modal-content">
            <button class="modal-close" onclick="closeEmailVerificationModal(true)">&times;</button>
            
            <!-- Email Verification Step -->
            <div id="emailVerificationStep" class="reset-step active">
                <div class="reset-header">
                    <h3>Verify Your Gmail</h3>
                    <p>We've sent a verification code to <span id="verificationEmail"></span></p>
                </div>
                <div class="otp-input">
                    <input type="text" maxlength="1" class="otp-digit" data-index="0" />
                    <input type="text" maxlength="1" class="otp-digit" data-index="1" />
                    <input type="text" maxlength="1" class="otp-digit" data-index="2" />
                    <input type="text" maxlength="1" class="otp-digit" data-index="3" />
                    <input type="text" maxlength="1" class="otp-digit" data-index="4" />
                    <input type="text" maxlength="1" class="otp-digit" data-index="5" />
                </div>
                <div class="otp-timer" id="emailVerificationTimer">Resend OTP in <span id="emailTimerCount">60</span>s</div>
                <div id="emailVerificationDevHint" style="text-align:center; color:#c00; font-weight:bold; margin-top:8px; display:none;"></div>
                <div style="text-align: center; margin: 20px 0;">
                    <button type="button" onclick="verifyEmailOTP()" class="submit-btn">Verify Email</button>
                </div>
                <div style="text-align: center;">
                    <button type="button" id="resendEmailBtn" class="resend-btn" onclick="resendEmailOTP()" disabled>Resend OTP</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Super Admin Modal -->
    <div id="superAdminModal">
        <div class="sa-modal-content">
            <button class="modal-close" onclick="closeSuperAdminModal(true)">&times;</button>

            <!-- Step 1: Super Admin Login -->
            <div id="saStep1" class="sa-step active">
                <div class="sa-header">
                    <h3>Super Admin Login</h3>
                    <p>Enter Super Admin credentials</p>
                </div>
                <form id="saLoginForm">
                    <div class="form-group">
                        <input type="email" id="saEmail" placeholder="Super Admin Email" required />
                    </div>
                    <div class="form-group">
                        <input type="password" id="saPassword" placeholder="Password" required />
                    </div>
                    <div style="text-align:center;">
                        <button type="button" onclick="superAdminLogin()" class="submit-btn">Log In</button>
                    </div>
                    <div style="text-align:center; margin-top:10px;">
                        <button type="button" onclick="openSuperAdminRegister()" class="resend-btn">Register Super Admin</button>
                    </div>
                </form>
            </div>

            <!-- Step 2: OTP Verification -->
            <div id="saStep2" class="sa-step">
                <div class="sa-header">
                    <h3>Verify OTP</h3>
                    <p>Enter the 6-digit code sent to <span id="saOtpEmail"></span></p>
                </div>
                <div class="otp-input">
                    <input type="text" maxlength="1" class="sa-otp-digit" data-index="0" />
                    <input type="text" maxlength="1" class="sa-otp-digit" data-index="1" />
                    <input type="text" maxlength="1" class="sa-otp-digit" data-index="2" />
                    <input type="text" maxlength="1" class="sa-otp-digit" data-index="3" />
                    <input type="text" maxlength="1" class="sa-otp-digit" data-index="4" />
                    <input type="text" maxlength="1" class="sa-otp-digit" data-index="5" />
                </div>
                <div class="sa-timer" id="saOtpTimer">Resend OTP in <span id="saTimerCount">60</span>s</div>
                <div class="sa-hint" id="saOtpDevHint"></div>
                <div style="text-align:center; margin: 16px 0;">
                    <button type="button" onclick="verifySuperAdminOTP()" class="submit-btn">Verify OTP</button>
                </div>
                <div style="text-align:center;">
                    <button type="button" id="saResendBtn" class="resend-btn" onclick="resendSuperAdminOTP()" disabled>Resend OTP</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Super Admin Registration Modal -->
    <div id="superAdminRegisterModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:10060; align-items:center; justify-content:center;">
        <div class="sa-modal-content">
            <button class="modal-close" onclick="closeSuperAdminRegister(true)">&times;</button>
            <div class="sa-header">
                <h3>Register Super Admin</h3>
                <p>Only one Super Admin account can be registered</p>
            </div>
            <form id="saRegisterForm">
                <div class="form-group"><input type="email" id="sarEmail" placeholder="Super Admin Email" required /></div>
                <div class="form-group"><input type="password" id="sarPassword" placeholder="Password (min 6)" required /></div>
                <div class="form-group"><input type="password" id="sarPassword2" placeholder="Confirm Password" required /></div>
                <div style="text-align:center;">
                    <button type="button" onclick="registerSuperAdmin()" class="submit-btn">Register</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Reset Modal -->
    <div id="passwordResetModal">
        <div class="reset-modal-content">
            <button class="modal-close" onclick="closePasswordResetModal(true)">&times;</button>
            
            <!-- Step 1: Email Input -->
            <div id="resetStep1" class="reset-step active">
                <div class="reset-header">
                    <h3>Reset Password</h3>
                    <p>Enter your Gmail address to receive an OTP</p>
                </div>
                <form id="resetForm1">
                    <div class="form-group">
                        <input type="email" id="resetEmail" placeholder="Gmail Address (e.g., user@gmail.com)" required pattern="[a-zA-Z0-9._%+-]+@gmail\.com$" title="Please enter a valid Gmail address" />
                    </div>
                    <button type="button" onclick="sendOTP()" class="submit-btn">Get OTP</button>
                </form>
            </div>

            <!-- Step 2: OTP Verification -->
            <div id="resetStep2" class="reset-step">
                <div class="reset-header">
                    <h3>Verify OTP</h3>
                    <p>Enter the 6-digit code sent to your mobile</p>
                </div>
                <div class="otp-input">
                    <input type="text" maxlength="1" class="otp-digit" data-index="0" />
                    <input type="text" maxlength="1" class="otp-digit" data-index="1" />
                    <input type="text" maxlength="1" class="otp-digit" data-index="2" />
                    <input type="text" maxlength="1" class="otp-digit" data-index="3" />
                    <input type="text" maxlength="1" class="otp-digit" data-index="4" />
                    <input type="text" maxlength="1" class="otp-digit" data-index="5" />
                </div>
                <div class="otp-timer" id="otpTimer">Resend OTP in <span id="timerCount">60</span>s</div>
                <div id="otpDevHint" style="text-align:center; color:#c00; font-weight:bold; margin-top:8px; display:none;"></div>
                <div style="text-align: center; margin: 20px 0;">
                    <button type="button" onclick="verifyOTP()" class="submit-btn">Verify OTP</button>
                </div>
                <div style="text-align: center;">
                    <button type="button" id="resendBtn" class="resend-btn" onclick="resendOTP()" disabled>Resend OTP</button>
                </div>
            </div>

            <!-- Step 3: New Password -->
            <div id="resetStep3" class="reset-step">
                <div class="reset-header">
                    <h3>New Password</h3>
                    <p>Enter your new password</p>
                </div>
                <form id="resetForm3">
                    <div class="form-group">
                        <input type="password" id="newPassword" placeholder="New Password" required />
                    </div>
                    <div class="form-group">
                        <input type="password" id="confirmPassword" placeholder="Confirm New Password" required />
                    </div>
                    <button type="button" onclick="updatePassword()" class="submit-btn">Update Password</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Captcha Modal -->
    <div id="captchaModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:10000; align-items:center; justify-content:center;">
        <div style="background:#ffffff; width:90%; max-width:420px; border-radius:14px; box-shadow:0 20px 60px rgba(0,0,0,.25); padding:20px; text-align:center;">
            <h3 style="margin:0 0 10px 0; color:#222;">This Message w</h3>
            <p style="margin:0 0 10px 0; color:#666; font-size:14px;">Enter the characters you see. New code in <span id="captchaTimer">60</span>s.</p>
            <div style="display:flex; align-items:center; justify-content:center; gap:10px; margin:10px 0 12px 0;">
                <canvas id="captchaCanvas" width="260" height="80" style="border-radius:8px; border:1px solid #e5e5e5; background:#f7f7f7;"></canvas>
                <button type="button" id="captchaRefresh" style="border-radius:8px; border:1px solid #ddd; background:#fafafa; color:#333; padding:10px 12px; cursor:pointer;">↻</button>
            </div>
            <div style="display:flex; gap:10px; justify-content:center;">
                <input id="captchaInput" type="text" autocomplete="off" placeholder="Type code" style="flex:1; min-width:0; border:1px solid #ddd; border-radius:10px; padding:10px 12px;" />
                <button type="button" id="captchaSubmit" style="border-radius:10px; border:1px solid transparent; background:linear-gradient(45deg,rgb(170, 39, 39),rgb(2, 3, 3)); color:#fff; padding:10px 16px; cursor:pointer;">Verify</button>
            </div>
            <p id="captchaError" style="margin:10px 0 0 0; color:#c00; font-size:13px; display:none;">Incorrect, try again.</p>
        </div>
    </div>

    <script>
        // Password Reset Modal Logic
        let currentResetStep = 1;
        let otpTimer = null;
        let otpCode = '';
        let resetToken = '';

        // Email Verification Modal Logic
        let emailVerificationTimer = null;
        let emailVerificationToken = '';
        let emailVerificationOTP = '';
        let isEmailVerified = false;

        function openPasswordResetModal() {
            document.getElementById('passwordResetModal').style.display = 'flex';
            resetToStep(1);
        }

        function closePasswordResetModal(triggeredByCloseBtn) {
            // If closing during verify OTP step and no input, show warning
            if (triggeredByCloseBtn && document.getElementById('resetStep2').classList.contains('active')) {
                const hasAnyDigit = Array.from(document.querySelectorAll('#passwordResetModal .otp-digit'))
                    .some(inp => (inp.value || '').trim() !== '');
                if (!hasAnyDigit) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'OTP Required',
                        text: 'Please enter the 6-digit OTP to proceed.',
                        confirmButtonColor: '#ffc107',
                        zIndex: 10001
                    });
                }
            }
            document.getElementById('passwordResetModal').style.display = 'none';
            resetToStep(1);
            clearOTPTimer();
        }

        // Email Verification Functions
        function openEmailVerificationModal(email) {
            document.getElementById('emailVerificationModal').style.display = 'flex';
            document.getElementById('verificationEmail').textContent = email;
            // Start countdown immediately when modal is shown
            startEmailVerificationTimer();
            sendEmailVerificationOTP(email);
        }

        function closeEmailVerificationModal(triggeredByCloseBtn) {
            // If closing during verify email OTP step and no input, show warning
            if (triggeredByCloseBtn && document.getElementById('emailVerificationStep').classList.contains('active')) {
                const hasAnyDigit = Array.from(document.querySelectorAll('#emailVerificationModal .otp-digit'))
                    .some(inp => (inp.value || '').trim() !== '');
                if (!hasAnyDigit) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'OTP Required',
                        text: 'Please enter the 6-digit OTP to verify your email.',
                        confirmButtonColor: '#ffc107',
                        zIndex: 10001
                    });
                }
            }
            document.getElementById('emailVerificationModal').style.display = 'none';
            clearEmailVerificationTimer();
            // Clear OTP inputs
            document.querySelectorAll('#emailVerificationModal .otp-digit').forEach(input => input.value = '');
        }

        function clearEmailVerificationTimer() {
            if (emailVerificationTimer) {
                clearInterval(emailVerificationTimer);
                emailVerificationTimer = null;
            }
        }

        function startEmailVerificationTimer() {
            let timeLeft = 60;
            const timerElement = document.getElementById('emailTimerCount');
            const resendBtn = document.getElementById('resendEmailBtn');
            
            clearEmailVerificationTimer();
            resendBtn.disabled = true;
            
            emailVerificationTimer = setInterval(() => {
                timeLeft--;
                timerElement.textContent = timeLeft;
                
                if (timeLeft <= 10) {
                    document.getElementById('emailVerificationTimer').classList.add('warning');
                }
                
                if (timeLeft <= 0) {
                    clearEmailVerificationTimer();
                    resendBtn.disabled = false;
                    document.getElementById('emailVerificationTimer').textContent = 'OTP expired. Click resend to get a new code.';
                    document.getElementById('emailVerificationTimer').classList.remove('warning');
                }
            }, 1000);
        }

        async function sendEmailVerificationOTP(email) {
            try {
                const response = await fetch('/email-verification/send-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ email })
                });

                let data = {};
                try { data = await response.json(); } catch(e) {}
                
                if (response.ok) {
                    emailVerificationToken = data.token;
                    emailVerificationOTP = data.otp;
                    // Timer already started on modal open; reset to full on success
                    startEmailVerificationTimer();
                    // Focus first OTP input
                    document.querySelector('#emailVerificationModal .otp-digit').focus();
                    
                    // Show debug OTP if in development mode
                    if (data.debug_otp) {
                        const devHint = document.getElementById('emailVerificationDevHint');
                        if (devHint) {
                            devHint.textContent = 'Development Mode - OTP: ' + data.debug_otp;
                            devHint.style.display = 'block';
                        }
                    }
                } else {
                    // Close any open modals first
                    document.getElementById('emailVerificationModal').style.display = 'none';
                    // Use setTimeout to ensure modal is closed before showing alert
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Send OTP',
                            text: data.message || 'Failed to send verification OTP',
                            confirmButtonColor: '#dc3545',
                            zIndex: 10001,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                    }, 100);
                }
            } catch (error) {
                // Close any open modals first
                document.getElementById('emailVerificationModal').style.display = 'none';
                // Use setTimeout to ensure modal is closed before showing alert
                setTimeout(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'Please try again.',
                        confirmButtonColor: '#dc3545',
                        zIndex: 10001,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });
                }, 100);
            }
        }

        function resendEmailOTP() {
            const email = document.getElementById('verificationEmail').textContent;
            sendEmailVerificationOTP(email);
        }

        async function verifyEmailOTP() {
            const otpDigits = document.querySelectorAll('#emailVerificationModal .otp-digit');
            const enteredOTP = Array.from(otpDigits).map(input => input.value).join('');
            
            if (enteredOTP.length !== 6) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete OTP',
                    text: 'Please enter complete 6-digit OTP',
                    confirmButtonColor: '#ffc107',
                    zIndex: 10001
                });
                return;
            }

            try {
                const response = await fetch('/email-verification/verify-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ 
                        token: emailVerificationToken, 
                        otp: enteredOTP, 
                        email: document.getElementById('verificationEmail').textContent 
                    })
                });

                let data = {};
                try { data = await response.json(); } catch(e) {}
                
                if (response.ok) {
                    isEmailVerified = true;
                    closeEmailVerificationModal();
                    // Enable the registration form
                    enableRegistrationForm();
                    Swal.fire({
                        icon: 'success',
                        title: 'Email Verified!',
                        text: 'You can now complete your registration.',
                        confirmButtonColor: '#28a745'
                    });
                } else {
                    // Close any open modals first
                    document.getElementById('emailVerificationModal').style.display = 'none';
                    // Use setTimeout to ensure modal is closed before showing alert
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid OTP',
                            text: data.message || 'Invalid OTP',
                            confirmButtonColor: '#dc3545',
                            zIndex: 10001,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(() => {
                            // Reopen modal after alert is closed
                            document.getElementById('emailVerificationModal').style.display = 'flex';
                            // Clear OTP inputs
                            otpDigits.forEach(input => input.value = '');
                            otpDigits[0].focus();
                        });
                    }, 100);
                }
            } catch (error) {
                // Close any open modals first
                document.getElementById('emailVerificationModal').style.display = 'none';
                // Use setTimeout to ensure modal is closed before showing alert
                setTimeout(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'Please try again.',
                        confirmButtonColor: '#dc3545',
                        zIndex: 10001,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        // Reopen modal after alert is closed
                        document.getElementById('emailVerificationModal').style.display = 'flex';
                    });
                }, 100);
            }
        }

        function enableRegistrationForm() {
            // Enable all form fields and submit button
            const form = document.querySelector('form[action="/register"]');
            const inputs = form.querySelectorAll('input, button');
            inputs.forEach(input => {
                input.disabled = false;
            });
            
            // Add visual indicator that email is verified
            const emailInput = form.querySelector('input[name="email"]');
            emailInput.style.borderColor = '#28a745';
            emailInput.style.backgroundColor = '#f8fff9';
            
            // Hide verification status and show success message
            const statusDiv = document.getElementById('emailVerificationStatus');
            statusDiv.style.display = 'block';
            statusDiv.innerHTML = '<strong>✅ Email Verified:</strong> Your Gmail account is now bound to infotech-inventory.com. You can complete your registration.';
            statusDiv.style.background = '#d4edda';
            statusDiv.style.borderColor = '#c3e6cb';
            statusDiv.style.color = '#155724';
        }

        function resetToStep(step) {
            currentResetStep = step;
            document.querySelectorAll('.reset-step').forEach(s => s.classList.remove('active'));
            document.getElementById(`resetStep${step}`).classList.add('active');
        }

        function clearOTPTimer() {
            if (otpTimer) {
                clearInterval(otpTimer);
                otpTimer = null;
            }
        }

        function startOTPTimer() {
            let timeLeft = 60;
            const timerElement = document.getElementById('timerCount');
            const resendBtn = document.getElementById('resendBtn');
            
            clearOTPTimer();
            resendBtn.disabled = true;
            
            otpTimer = setInterval(() => {
                timeLeft--;
                timerElement.textContent = timeLeft;
                
                if (timeLeft <= 10) {
                    document.getElementById('otpTimer').classList.add('warning');
                }
                
                if (timeLeft <= 0) {
                    clearOTPTimer();
                    resendBtn.disabled = false;
                    document.getElementById('otpTimer').textContent = 'OTP expired. Click resend to get a new code.';
                    document.getElementById('otpTimer').classList.remove('warning');
                }
            }, 1000);
        }

        async function sendOTP() {
            const email = document.getElementById('resetEmail').value;
            if (!email) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Email Required',
                    text: 'Please enter a valid email address',
                    confirmButtonColor: '#ffc107',
                    zIndex: 10001
                });
                return;
            }

            // Immediately show Verify OTP step for a faster UX
            resetToStep(2);
            // Prepare UI while waiting for the server
            const timerEl = document.getElementById('otpTimer');
            const countEl = document.getElementById('timerCount');
            const resendBtn = document.getElementById('resendBtn');
            if (timerEl) timerEl.classList.remove('warning');
            if (countEl) countEl.textContent = '60';
            if (resendBtn) resendBtn.disabled = true;
            // Focus the first digit input right away
            const firstOtpInput = document.querySelector('.otp-digit');
            if (firstOtpInput) firstOtpInput.focus();
            // Start countdown immediately when step 2 shows
            startOTPTimer();

            try {
                const response = await fetch('/password-reset/send-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ email })
                });

                let data = {};
                try { data = await response.json(); } catch(e) {}
                
                if (response.ok) {
                    resetToken = data.token;
                    startOTPTimer();
                    // Focus first OTP input
                    document.querySelector('.otp-digit').focus();
                    
                    // Show debug OTP if in development mode
                    if (data.debug_otp) {
                        const devHint = document.getElementById('otpDevHint');
                        if (devHint) {
                            devHint.textContent = 'Development Mode - OTP: ' + data.debug_otp;
                            devHint.style.display = 'block';
                        }
                    }
                } else {
                    // Close any open modals first
                    document.getElementById('passwordResetModal').style.display = 'none';
                    // Use setTimeout to ensure modal is closed before showing alert
                    setTimeout(() => {
                        if (response.status === 429 && data.locked_until) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Too Many Attempts',
                                text: 'Try again later.',
                                confirmButtonColor: '#dc3545',
                                zIndex: 10001,
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            }).then(() => { resetToStep(1); document.getElementById('passwordResetModal').style.display = 'flex'; });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed to Send OTP',
                                text: data.message || 'Failed to send OTP',
                                confirmButtonColor: '#dc3545',
                                zIndex: 10001,
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            }).then(() => { resetToStep(1); document.getElementById('passwordResetModal').style.display = 'flex'; });
                        }
                    }, 100);
                }
            } catch (error) {
                // Close any open modals first
                document.getElementById('passwordResetModal').style.display = 'none';
                // Use setTimeout to ensure modal is closed before showing alert
                setTimeout(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'Please try again.',
                        confirmButtonColor: '#dc3545',
                        zIndex: 10001,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => { resetToStep(1); document.getElementById('passwordResetModal').style.display = 'flex'; });
                }, 100);
            }
        }

        function resendOTP() {
            sendOTP();
        }

        async function verifyOTP() {
            const otpDigits = document.querySelectorAll('.otp-digit');
            const enteredOTP = Array.from(otpDigits).map(input => input.value).join('');
            
            if (enteredOTP.length !== 6) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete OTP',
                    text: 'Please enter complete 6-digit OTP',
                    confirmButtonColor: '#ffc107',
                    zIndex: 10001
                });
                return;
            }

            try {
                const response = await fetch('/password-reset/verify-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ token: resetToken, otp: enteredOTP, email: document.getElementById('resetEmail').value })
                });

                let data = {};
                try { data = await response.json(); } catch(e) {}
                
                if (response.ok) {
                    resetToStep(3);
                    clearOTPTimer();
                } else {
                    // Close any open modals first
                    document.getElementById('passwordResetModal').style.display = 'none';
                    // Use setTimeout to ensure modal is closed before showing alert
                    setTimeout(() => {
                        if (response.status === 429 && data.locked_until) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Too Many Invalid Attempts',
                                text: 'Try again in 5 minutes.',
                                confirmButtonColor: '#dc3545',
                                zIndex: 10001,
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            });
                        } else if (data && data.remaining_attempts !== undefined) {
                            // Show attempts remaining in a separate, more prominent alert
                            Swal.fire({
                                icon: 'warning',
                                title: 'Invalid OTP',
                                text: data.message || 'Invalid OTP',
                                confirmButtonColor: '#ffc107',
                                zIndex: 10001,
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            }).then(() => {
                                // Show attempts remaining alert after the first one is closed
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Attempts Remaining',
                                    text: `You have ${data.remaining_attempts} attempt${data.remaining_attempts === 1 ? '' : 's'} left before your account is temporarily locked.`,
                                    confirmButtonColor: '#17a2b8',
                                    confirmButtonText: 'Try Again',
                                    zIndex: 10001,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false
                                }).then(() => {
                                    // Reopen modal after alert is closed
                                    document.getElementById('passwordResetModal').style.display = 'flex';
                                    // Clear OTP inputs
                                    otpDigits.forEach(input => input.value = '');
                                    otpDigits[0].focus();
                                });
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid OTP',
                                text: data.message || 'Invalid OTP',
                                confirmButtonColor: '#dc3545',
                                zIndex: 10001,
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            }).then(() => {
                                // Reopen modal after alert is closed
                                document.getElementById('passwordResetModal').style.display = 'flex';
                                // Clear OTP inputs
                                otpDigits.forEach(input => input.value = '');
                                otpDigits[0].focus();
                            });
                        }
                    }, 100);
                }
            } catch (error) {
                // Close any open modals first
                document.getElementById('passwordResetModal').style.display = 'none';
                // Use setTimeout to ensure modal is closed before showing alert
                setTimeout(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'Please try again.',
                        confirmButtonColor: '#dc3545',
                        zIndex: 10001,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        // Reopen modal after alert is closed
                        document.getElementById('passwordResetModal').style.display = 'flex';
                    });
                }, 100);
            }
        }

        async function updatePassword() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (newPassword.length < 6) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Password Too Short',
                    text: 'Password must be at least 6 characters',
                    confirmButtonColor: '#ffc107',
                    zIndex: 10001
                });
                return;
            }
            
            if (newPassword !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Passwords Do Not Match',
                    text: 'Please make sure both passwords are identical',
                    confirmButtonColor: '#dc3545',
                    zIndex: 10001
                });
                return;
            }

            try {
                const response = await fetch('/password-reset/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ token: resetToken, password: newPassword, password_confirmation: confirmPassword })
                });

                let data = {};
                try { data = await response.json(); } catch(e) {}
                
                if (response.ok) {
                    // Close any open modals first
                    document.getElementById('passwordResetModal').style.display = 'none';
                    Swal.fire({
                        icon: 'success',
                        title: 'Password Updated!',
                        text: 'You can now login with your new password.',
                        confirmButtonColor: '#28a745',
                        zIndex: 10001
                    }).then(() => {
                        closePasswordResetModal();
                    });
                } else {
                    // Close any open modals first
                    document.getElementById('passwordResetModal').style.display = 'none';
                    // Use setTimeout to ensure modal is closed before showing alert
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Update Password',
                            text: data.message || 'Failed to update password',
                            confirmButtonColor: '#dc3545',
                            zIndex: 10001,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(() => {
                            // Reopen modal after alert is closed
                            document.getElementById('passwordResetModal').style.display = 'flex';
                        });
                    }, 100);
                }
            } catch (error) {
                // Close any open modals first
                document.getElementById('passwordResetModal').style.display = 'none';
                // Use setTimeout to ensure modal is closed before showing alert
                setTimeout(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'Please try again.',
                        confirmButtonColor: '#dc3545',
                        zIndex: 10001,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        // Reopen modal after alert is closed
                        document.getElementById('passwordResetModal').style.display = 'flex';
                    });
                }, 100);
            }
        }

        // OTP Input Navigation
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-digit');
            
            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    const value = e.target.value;
                    if (value.length === 1 && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                });
                
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });
            });

            // Email verification on blur for registration form
            const emailInput = document.querySelector('form[action="/register"] input[name="email"]');
            if (emailInput) {
                emailInput.addEventListener('blur', function() {
                    const email = this.value.trim();
                    const gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
                    
                    if (email && gmailRegex.test(email) && !isEmailVerified) {
                        // Check if this email is already verified
                        if (emailVerificationToken && document.getElementById('verificationEmail').textContent === email) {
                            return; // Already verified this email
                        }
                        // Show verification status
                        document.getElementById('emailVerificationStatus').style.display = 'block';
                        openEmailVerificationModal(email);
                    } else if (email && !gmailRegex.test(email)) {
                        // Show error for non-Gmail
                        document.getElementById('emailVerificationStatus').style.display = 'block';
                        document.getElementById('emailVerificationStatus').innerHTML = '<strong>❌ Invalid Email:</strong> Please use a valid Gmail address (e.g., user@gmail.com)';
                        document.getElementById('emailVerificationStatus').style.background = '#f8d7da';
                        document.getElementById('emailVerificationStatus').style.borderColor = '#f5c6cb';
                        document.getElementById('emailVerificationStatus').style.color = '#721c24';
                    }
                });

                emailInput.addEventListener('input', function() {
                    // Hide status when user starts typing
                    if (this.value.trim() === '') {
                        document.getElementById('emailVerificationStatus').style.display = 'none';
                    }
                });
            }

            // Disable registration form initially until email is verified
            const registrationForm = document.querySelector('form[action="/register"]');
            if (registrationForm) {
                const formInputs = registrationForm.querySelectorAll('input:not([name="email"]), button');
                formInputs.forEach(input => {
                    input.disabled = true;
                });
            }
        });

        // Simple CAPTCHA with rotation, noise and 60s auto refresh
        (function(){
            let currentSolution = '';
            let expiryTs = 0;
            let timerId = null;

            function randomString(len){
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
                let s = '';
                for(let i=0;i<len;i++){ s += chars.charAt(Math.floor(Math.random()*chars.length)); }
                return s;
            }

            function drawCaptcha(text){
                const canvas = document.getElementById('captchaCanvas'); if(!canvas) return;
                const ctx = canvas.getContext('2d');
                // background
                ctx.fillStyle = '#f3f6fa';
                ctx.fillRect(0,0,canvas.width,canvas.height);
                // noise lines
                for(let i=0;i<5;i++){
                    ctx.strokeStyle = `rgba(${100+Math.random()*100|0},${100+Math.random()*100|0},${100+Math.random()*100|0},0.7)`;
                    ctx.lineWidth = 1 + Math.random()*2;
                    ctx.beginPath();
                    ctx.moveTo(Math.random()*canvas.width, Math.random()*canvas.height);
                    ctx.lineTo(Math.random()*canvas.width, Math.random()*canvas.height);
                    ctx.stroke();
                }
                // characters
                const cw = canvas.width; const ch = canvas.height;
                const spacing = cw / (text.length + 1);
                for(let i=0;i<text.length;i++){
                    const chrs = text[i];
                    const x = spacing*(i+1);
                    const y = ch/2 + (Math.random()*10-5);
                    const angle = (Math.random()*0.6 - 0.3);
                    ctx.save();
                    ctx.translate(x,y);
                    ctx.rotate(angle);
                    ctx.font = `${40 + Math.floor(Math.random()*8)}px Poppins, Arial`;
                    ctx.fillStyle = `rgb(${50+Math.random()*150|0},${50+Math.random()*150|0},${50+Math.random()*150|0})`;
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(chrs, 0, 0);
                    ctx.restore();
                }
                // noise dots
                for(let i=0;i<80;i++){
                    ctx.fillStyle = `rgba(0,0,0,${Math.random()*0.15})`;
                    ctx.fillRect(Math.random()*cw, Math.random()*ch, 1, 1);
                }
            }

            function regenerate(){
                currentSolution = randomString(6);
                expiryTs = Date.now() + 60*1000;
                drawCaptcha(currentSolution);
                startTimer();
                const err = document.getElementById('captchaError'); if(err){ err.style.display='none'; }
                const input = document.getElementById('captchaInput'); if(input){ input.value=''; input.focus(); }
            }

            function startTimer(){
                const el = document.getElementById('captchaTimer');
                function tick(){
                    const rem = Math.max(0, expiryTs - Date.now());
                    const s = Math.ceil(rem/1000);
                    if(el) el.textContent = String(s);
                    if(rem<=0){ regenerate(); }
                }
                if(timerId) clearInterval(timerId);
                timerId = setInterval(tick, 500);
                tick();
            }

            window.openCaptchaModal = function(onSolved){
                const modal = document.getElementById('captchaModal');
                if(!modal) return;
                modal.style.display='flex';
                regenerate();
                const refresh = document.getElementById('captchaRefresh');
                if(refresh){ refresh.onclick = regenerate; }
                const submit = document.getElementById('captchaSubmit');
                const input = document.getElementById('captchaInput');
                const err = document.getElementById('captchaError');
                function trySolve(){
                    const val = (input.value || '').toUpperCase().replace(/\s+/g,'');
                    if(val === currentSolution){
                        modal.style.display='none';
                        clearInterval(timerId); timerId=null;
                        onSolved && onSolved();
                    } else {
                        if(err) err.style.display='block';
                        regenerate();
                    }
                }
                if(submit){ submit.onclick = trySolve; }
                if(input){ input.onkeypress = function(e){ if(e.key==='Enter'){ e.preventDefault(); trySolve(); } } }
            }
        })();
    </script>
</body>
</html>