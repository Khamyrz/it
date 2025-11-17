<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - InfoTech</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #0a1128;
            overflow-x: hidden;
        }

        body.no-scroll {
            overflow: hidden;
        }

        .splash-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #1a1a1a;
            z-index: 9999;
            transition: none;
        }

        /* After the intro animation, turn the splash into a normal
           first "page" section so you can scroll back to it */
        .splash-container.animation-complete {
            position: relative;
            top: 0;
            left: 0;
            z-index: 1;
            height: 100vh;
            transition: opacity 0.8s ease;
        }

        .splash-container.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .bars-container {
            display: flex;
            width: 100%;
            height: 100vh;
            position: absolute;
            z-index: 2;
        }

        .bar {
            width: 20%;
            height: 100vh;
            background: #0C1B51;
            animation: slideUp 1.5s ease-in-out forwards;
            position: relative;
        }

        .bar:nth-child(1) {
            animation-delay: 0s;
        }

        .bar:nth-child(2) {
            animation-delay: 0.4s;
        }

        .bar:nth-child(3) {
            animation-delay: 0.8s;
        }

        .bar:nth-child(4) {
            animation-delay: 1.2s;
        }

        .bar:nth-child(5) {
            animation-delay: 1.6s;
        }

        @keyframes slideUp {
            0% {
                transform: translateY(0);
            }
            100% {
                transform: translateY(-110vh);
            }
        }

        .logo-container {
            position: absolute;
            z-index: 1;
            opacity: 1;
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo-container img {
            width: 100vw;
            height: 100vh;
            object-fit: cover;
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            image-rendering: high-quality;
            -ms-interpolation-mode: bicubic;
        }

        .main-content {
            display: none;
            background: transparent;
            position: relative;
        }

        .main-content.active {
            display: block;
        }

        .parallax-section {
            height: 200vh;
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #0a1128;
        }

        .parallax-bg {
            position: absolute;
            width: 100%;
            height: 150%;
            background: radial-gradient(circle at 30% 50%, rgba(139, 69, 172, 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 70% 60%, rgba(66, 133, 244, 0.2) 0%, transparent 50%);
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 150%;
        }

        .shape {
            position: absolute;
            opacity: 0;
            transition: opacity 1s ease;
        }

        .shape.visible {
            opacity: 0.6;
        }

        .square-outline {
            width: 150px;
            height: 150px;
            border: 3px solid #00d4ff;
            border-radius: 20px;
            top: 5%;
            left: 8%;
            transform: rotate(15deg);
        }

        .circle-teal {
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, #1a7f8e, transparent);
            border-radius: 50%;
            top: -5%;
            right: 5%;
        }

        .circle-pink {
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, #d946a6, transparent);
            border-radius: 50%;
            bottom: -10%;
            left: -5%;
        }

        .square-gradient {
            width: 180px;
            height: 180px;
            background: linear-gradient(135deg, #00d4ff, #d946a6);
            border-radius: 30px;
            bottom: 15%;
            right: 8%;
            opacity: 0.4;
        }

        .ring {
            width: 300px;
            height: 300px;
            border: 20px solid rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: 40%;
            right: -5%;
        }

        .dots-grid {
            position: absolute;
            display: grid;
            grid-template-columns: repeat(3, 8px);
            gap: 12px;
            opacity: 0;
            transition: opacity 1s ease;
        }

        .dots-grid.visible {
            opacity: 1;
        }

        .dots-grid.top-left {
            top: 45%;
            left: 10%;
        }

        .dots-grid.bottom-right {
            bottom: 35%;
            right: 15%;
        }

        .dot {
            width: 8px;
            height: 8px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
        }

        .geometric-shapes {
            position: absolute;
            opacity: 0;
            transition: opacity 1s ease;
        }

        .geometric-shapes.visible {
            opacity: 1;
        }

        .diamond {
            width: 15px;
            height: 15px;
            background: #fbbf24;
            transform: rotate(45deg);
            position: absolute;
        }

        .diamond.d1 { top: 15%; left: 40%; }
        .diamond.d2 { top: 60%; left: 48%; }
        .diamond.d3 { bottom: 25%; right: 25%; }

        .triangle {
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-bottom: 17px solid #d946a6;
            position: absolute;
        }

        .triangle.t1 { top: 45%; right: 12%; }
        .triangle.t2 { bottom: 42%; right: 8%; }

        .small-diamond {
            width: 12px;
            height: 12px;
            background: white;
            transform: rotate(45deg);
            position: absolute;
        }

        .small-diamond.sd1 { top: 12%; left: 35%; }
        .small-diamond.sd2 { top: 25%; right: 8%; }
        .small-diamond.sd3 { bottom: 30%; right: 35%; }

        .content-cards {
            position: relative;
            z-index: 10;
            display: flex;
            gap: 70px; /* more space between icons */
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            padding: 0 80px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 30px;
            padding: 50px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, opacity 1s ease;
            opacity: 0;
            flex: 0 0 260px; /* fixed width so cards are clearly separated */
        }

        .card.visible {
            opacity: 1;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        .card img {
            width: 180px;
            height: 180px;
            object-fit: contain;
        }

        .chart-card {
            background: rgba(30, 41, 59, 0.9);
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            opacity: 0;
            transition: opacity 1s ease;
        }

        .chart-card.visible {
            opacity: 1;
        }

        .chart-bars {
            display: flex;
            align-items: flex-end;
            gap: 15px;
            height: 150px;
        }

        .bar-chart {
            width: 40px;
            border-radius: 8px 8px 0 0;
        }

        .bar-chart.b1 { height: 60%; background: #64748b; }
        .bar-chart.b2 { height: 80%; background: #06b6d4; }
        .bar-chart.b3 { height: 100%; background: #10b981; }
        .bar-chart.b4 { height: 90%; background: #f59e0b; }
        .bar-chart.b5 { height: 110%; background: #ef4444; }

        .chart-dots {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .chart-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .chart-dot.cd1 { background: #64748b; }
        .chart-dot.cd2 { background: #06b6d4; }
        .chart-dot.cd3 { background: #10b981; }
        .chart-dot.cd4 { background: #f59e0b; }
        .chart-dot.cd5 { background: #ef4444; }

        .text-section {
            position: relative;
            margin-top: 60px; /* sits just under the cards */
            text-align: center;
            width: 100%;
            z-index: 10;
        }

        .text-line {
            color: white;
            font-size: 3.2rem;
            font-weight: 700;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            margin: 10px 0;
            opacity: 0;
            transform: translateY(30px) scale(0.9);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .text-line.visible {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .text-line:nth-child(1) {
            transition-delay: 0s;
        }

        .text-line:nth-child(2) {
            transition-delay: 0.15s;
        }

        .text-line:nth-child(3) {
            transition-delay: 0.3s;
        }

        /* ----- Vision section styles ----- */
        .vision-section {
            position: relative;
            min-height: 140vh;
            background: radial-gradient(circle at top right, rgba(118,0,255,0.25), transparent 45%), #050b1e;
            padding: 120px 6vw 200px;
            overflow: visible;
            display: flex;
            align-items: flex-start;
        }

        .vision-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, 0.9fr);
            gap: 60px;
            align-items: center;
            width: 100%;
            position: relative;
            z-index: 2;
        }

        .vision-text-block {
            perspective: 1000px;
        }

        .vision-column {
            transform: rotateY(-16deg) skewY(-4deg);
            transform-origin: left center;
            color: white;
            text-shadow: 0 15px 35px rgba(0,0,0,0.6);
        }

        .vision-text-line {
            font-size: clamp(2rem, 3.4vw, 3.4rem);
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 2px;
            margin: 12px 0;
            opacity: 0;
            transform: translateY(25px) scale(0.95);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .vision-text-line span.gradient {
            background: linear-gradient(120deg, #e879f9, #7c3aed, #06b6d4);
            -webkit-background-clip: text;
            color: transparent;
        }

        .vision-text-line.visible {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .vision-text-line.secondary {
            font-size: clamp(1rem, 1.8vw, 1.8rem);
            text-transform: none;
            font-weight: 600;
        }

        .vision-right {
            display: flex;
            flex-direction: column;
            gap: 25px;
            align-items: flex-end;
        }

        .vision-card {
            width: 240px;
            padding: 30px;
            border-radius: 28px;
            background: rgba(15, 23, 42, 0.9);
            box-shadow: 0 20px 60px rgba(0,0,0,0.45);
            border: 1px solid rgba(255,255,255,0.06);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .vision-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .vision-card svg {
            width: 140px;
            height: 140px;
        }

        .vision-deco {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .vision-deco .orb {
            position: absolute;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(232,121,249,0.35), transparent 70%);
            filter: blur(2px);
        }

        .vision-deco .orb.pink { bottom: -60px; left: -120px; }
        .vision-deco .orb.teal { top: -120px; right: -60px; background: radial-gradient(circle, rgba(45,212,191,0.35), transparent 70%); }

        .vision-deco .streak {
            position: absolute;
            width: 120px;
            height: 4px;
            background: linear-gradient(90deg, transparent, rgba(99,102,241,0.8));
            transform: rotate(-30deg);
            opacity: 0.5;
        }

        .vision-deco .streak:nth-child(3) { top: 10%; left: 30%; }
        .vision-deco .streak:nth-child(4) { top: 25%; right: 25%; width: 180px; }
        .vision-deco .streak:nth-child(5) { bottom: 15%; left: 45%; width: 90px; }

        /* ----- Connected scan section ----- */
        .connected-section {
            position: relative;
            min-height: 150vh;
            background: #050b1e;
            padding: 120px 6vw 220px;
            overflow: visible;
            display: flex;
            align-items: flex-start;
        }

        .connected-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);
            gap: 50px;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .connected-heading {
            font-size: clamp(1.8rem, 3.2vw, 3.6rem);
            font-weight: 800;
            line-height: 1.15;
            color: white;
            margin-bottom: 30px;
            opacity: 0;
            transform: translateY(25px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .connected-heading span {
            display: block;
            background: linear-gradient(120deg, #a855f7, #6366f1, #22d3ee);
            -webkit-background-clip: text;
            color: transparent;
        }

        .connected-description {
            color: rgba(255, 255, 255, 0.85);
            font-size: 1.1rem;
            max-width: 600px;
            line-height: 1.7;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s ease 0.1s, transform 0.8s ease 0.1s;
        }

        .connected-heading.visible,
        .connected-description.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .connected-right {
            display: flex;
            flex-direction: column;
            gap: 30px;
            align-items: flex-end;
        }

        .connected-card {
            width: 240px;
            padding: 28px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
            opacity: 0;
            transform: translateY(40px) scale(0.95);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .connected-card.visible {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .connected-card svg {
            width: 100%;
            height: 120px;
        }

        .connected-deco {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        .connected-deco .bubble {
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.35), transparent 70%);
            filter: blur(2px);
        }

        .connected-deco .bubble:nth-child(1) { top: 5%; left: -80px; }
        .connected-deco .bubble:nth-child(2) { bottom: -60px; right: -40px; background: radial-gradient(circle, rgba(99,102,241,0.35), transparent 70%); }

        .connected-deco .ring {
            position: absolute;
            width: 140px;
            height: 140px;
            border: 4px solid rgba(56, 189, 248, 0.5);
            border-radius: 50%;
        }

        .connected-deco .ring:nth-child(3) { top: 20%; right: 15%; }
        .connected-deco .ring:nth-child(4) { bottom: 20%; left: 12%; width: 110px; height: 110px; }

        .scroll-indicator {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10000;
            text-align: center;
            color: white;
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .scroll-indicator.visible {
            opacity: 1;
        }

        .scroll-indicator .arrow {
            width: 30px;
            height: 30px;
            border-left: 3px solid white;
            border-bottom: 3px solid white;
            transform: rotate(-45deg);
            margin: 10px auto;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: rotate(-45deg) translateY(0);
            }
            40% {
                transform: rotate(-45deg) translateY(10px);
            }
            60% {
                transform: rotate(-45deg) translateY(5px);
            }
        }

        .scroll-counter {
            font-weight: bold;
            font-size: 16px;
            margin-top: 5px;
        }

        .get-started-btn {
            position: fixed;
            top: 30px;
            right: 40px;
            z-index: 10001;
            background: linear-gradient(135deg, #00ff88, #36d399);
            color: #031225;
            font-weight: 700;
            text-transform: uppercase;
            border: none;
            border-radius: 999px;
            padding: 14px 28px;
            letter-spacing: 1px;
            text-decoration: none;
            box-shadow: 0 12px 30px rgba(0, 255, 136, 0.35);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .get-started-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 35px rgba(0, 255, 136, 0.45);
        }
    </style>
</head>
<body class="no-scroll">
    <div class="splash-container" id="splash">
        <div class="logo-container">
            <img src="{{ asset('infotech.png') }}" alt="InfoTech Logo">
        </div>
        <div class="bars-container">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
    </div>

    <div class="scroll-indicator" id="scrollIndicator">
        <div>Scroll down to continue</div>
        <div class="arrow"></div>
        <div class="scroll-counter"><span id="scrollCount">0</span> / 5</div>
    </div>

    <a href="{{ route('login') }}" class="get-started-btn">Get Started</a>

    <div class="main-content" id="mainContent">
        <section class="parallax-section">
            <div class="parallax-bg" data-speed="0.5"></div>
            
            <div class="floating-shapes">
                <div class="shape square-outline" data-speed="0.3"></div>
                <div class="shape circle-teal" data-speed="0.4"></div>
                <div class="shape circle-pink" data-speed="0.6"></div>
                <div class="shape square-gradient" data-speed="0.35"></div>
                <div class="shape ring" data-speed="0.45"></div>
                
                <div class="dots-grid top-left" data-speed="0.25">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
                
                <div class="dots-grid bottom-right" data-speed="0.2">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>

                <div class="geometric-shapes" data-speed="0.5">
                    <div class="diamond d1"></div>
                    <div class="diamond d2"></div>
                    <div class="diamond d3"></div>
                    <div class="triangle t1"></div>
                    <div class="triangle t2"></div>
                    <div class="small-diamond sd1"></div>
                    <div class="small-diamond sd2"></div>
                    <div class="small-diamond sd3"></div>
                </div>
            </div>

            <div class="content-cards">
                <!-- Line graph card -->
                <div class="card" data-speed="0.15">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200'%3E%3Cdefs%3E%3ClinearGradient id='lg1' x1='0%25' y1='100%25' x2='100%25' y2='0%25'%3E%3Cstop offset='0%25' stop-color='%2306b6d4'/%3E%3Cstop offset='100%25' stop-color='%234f46e5'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect x='30' y='30' width='140' height='120' rx='16' fill='%23f9fafb'/%3E%3Cpolyline points='40,130 80,90 115,110 150,60' fill='none' stroke='url(%23lg1)' stroke-width='6' stroke-linecap='round' stroke-linejoin='round'/%3E%3Ccircle cx='80' cy='90' r='5' fill='%2306b6d4'/%3E%3Ccircle cx='115' cy='110' r='5' fill='%2306b6d4'/%3E%3Ccircle cx='150' cy='60' r='7' fill='%234f46e5'/%3E%3C/svg%3E" alt="Line Graph">
                </div>
                
                <!-- Barcode scanner card -->
                <div class="card" data-speed="0.2">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200'%3E%3Crect x='30' y='40' width='140' height='120' rx='16' fill='%23f9fafb'/%3E%3Crect x='45' y='80' width='5' height='60' fill='%231e293b'/%3E%3Crect x='55' y='70' width='8' height='70' fill='%2306b6d4'/%3E%3Crect x='70' y='80' width='4' height='60' fill='%231e293b'/%3E%3Crect x='80' y='75' width='6' height='65' fill='%23e11d48'/%3E%3Crect x='92' y='80' width='4' height='60' fill='%231e293b'/%3E%3Crect x='102' y='70' width='8' height='70' fill='%2306b6d4'/%3E%3Crect x='118' y='80' width='4' height='60' fill='%231e293b'/%3E%3Crect x='128' y='75' width='6' height='65' fill='%23e11d48'/%3E%3Crect x='140' y='80' width='5' height='60' fill='%231e293b'/%3E%3Crect x='45' y='70' width='100' height='4' fill='rgba(148,163,184,0.4)'/%3E%3Crect x='45' y='142' width='100' height='4' fill='rgba(148,163,184,0.4)'/%3E%3C/svg%3E" alt="Barcode Scanner">
                </div>

                <!-- Repair / tools card -->
                <div class="chart-card" data-speed="0.18">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 140" style="width:100%;height:100%;">
                        <defs>
                            <linearGradient id="rg1" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#f59e0b"/>
                                <stop offset="100%" stop-color="#ef4444"/>
                            </linearGradient>
                        </defs>
                        <rect x="10" y="10" width="180" height="120" rx="24" fill="rgba(15,23,42,0.95)"/>
                        <!-- Wrench + gear combo -->
                        <circle cx="80" cy="70" r="26" fill="none" stroke="#e5e7eb" stroke-width="4"/>
                        <path d="M70 55 L88 73 L80 81 L62 63 Z" fill="#f97316"/>
                        <path d="M95 60 a14 14 0 1 1 -10 24" fill="none" stroke="url(#rg1)" stroke-width="6" stroke-linecap="round"/>
                        <!-- Small bolts -->
                        <circle cx="130" cy="55" r="4" fill="#e5e7eb"/>
                        <circle cx="145" cy="75" r="4" fill="#e5e7eb"/>
                        <circle cx="135" cy="95" r="4" fill="#e5e7eb"/>
                    </svg>
                </div>
            </div>

            <div class="text-section" id="heroText">
                <div class="text-line">Smarter Tools.</div>
                <div class="text-line">Faster Tracking.</div>
                <div class="text-line">Better Control.</div>
            </div>
        </section>

        <section class="vision-section" id="visionSection">
            <div class="vision-deco">
                <div class="orb pink" data-speed="0.1"></div>
                <div class="orb teal" data-speed="0.18"></div>
                <div class="streak" data-speed="0.2"></div>
                <div class="streak" data-speed="0.22"></div>
                <div class="streak" data-speed="0.25"></div>
                <div class="streak" data-speed="0.28"></div>
            </div>

            <div class="vision-grid">
                <div class="vision-text-block" data-speed="0.15">
                    <div class="vision-column">
                        <div class="vision-text-line"><span class="gradient">Empower</span></div>
                        <div class="vision-text-line"><span class="gradient">Without</span></div>
                        <div class="vision-text-line"><span class="gradient">Limits</span></div>
                    </div>
                    <div class="vision-column" style="margin-top:30px;">
                        <div class="vision-text-line secondary">Seamless integration,</div>
                        <div class="vision-text-line secondary">limitless control.</div>
                        <div class="vision-text-line secondary">Built to adapt.</div>
                        <div class="vision-text-line secondary">Ready to lead.</div>
                    </div>
                </div>

                <div class="vision-right" data-speed="0.25">
                    <!-- Inventory dashboard icon -->
                    <div class="vision-card">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                            <rect x="24" y="30" width="152" height="140" rx="26" fill="#020617"/>
                            <rect x="44" y="55" width="52" height="26" rx="6" fill="#0f172a" stroke="#38bdf8" stroke-width="3"/>
                            <rect x="104" y="55" width="52" height="26" rx="6" fill="#0f172a" stroke="#22c55e" stroke-width="3"/>
                            <rect x="44" y="92" width="112" height="10" rx="3" fill="#1e293b"/>
                            <rect x="44" y="110" width="62" height="10" rx="3" fill="#1e293b"/>
                            <rect x="44" y="128" width="92" height="10" rx="3" fill="#1e293b"/>
                            <circle cx="150" cy="115" r="6" fill="#f97316"/>
                            <circle cx="150" cy="135" r="6" fill="#22c55e"/>
                        </svg>
                    </div>
                    <!-- Server rack / assets icon -->
                    <div class="vision-card">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                            <rect x="40" y="25" width="120" height="150" rx="24" fill="#020617"/>
                            <rect x="60" y="45" width="80" height="30" rx="8" fill="#0b1120" stroke="#22d3ee" stroke-width="3"/>
                            <rect x="60" y="85" width="80" height="30" rx="8" fill="#0b1120" stroke="#22d3ee" stroke-width="3"/>
                            <rect x="60" y="125" width="80" height="30" rx="8" fill="#0b1120" stroke="#22d3ee" stroke-width="3"/>
                            <circle cx="72" cy="60" r="3" fill="#22c55e"/>
                            <circle cx="84" cy="60" r="3" fill="#22c55e"/>
                            <circle cx="96" cy="60" r="3" fill="#22c55e"/>
                            <circle cx="72" cy="100" r="3" fill="#22c55e"/>
                            <circle cx="84" cy="100" r="3" fill="#22c55e"/>
                            <circle cx="96" cy="100" r="3" fill="#22c55e"/>
                            <circle cx="72" cy="140" r="3" fill="#22c55e"/>
                            <circle cx="84" cy="140" r="3" fill="#22c55e"/>
                            <circle cx="96" cy="140" r="3" fill="#22c55e"/>
                        </svg>
                    </div>
                    <!-- Sync / lifecycle icon -->
                    <div class="vision-card">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                            <rect x="30" y="30" width="140" height="140" rx="32" fill="#020617"/>
                            <circle cx="100" cy="100" r="42" fill="#0b1120"/>
                            <path d="M115 70 L135 70 L135 90" fill="none" stroke="#38bdf8" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M85 130 L65 130 L65 110" fill="none" stroke="#22c55e" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M80 80 A24 24 0 0 1 120 90" fill="none" stroke="#38bdf8" stroke-width="6" stroke-linecap="round"/>
                            <path d="M120 120 A24 24 0 0 1 80 110" fill="none" stroke="#22c55e" stroke-width="6" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <section class="connected-section" id="connectedSection">
            <div class="connected-deco">
                <div class="bubble" data-speed="0.12"></div>
                <div class="bubble" data-speed="0.18"></div>
                <div class="ring" data-speed="0.22"></div>
                <div class="ring" data-speed="0.26"></div>
            </div>
            <div class="connected-grid">
                <div data-speed="0.15">
                    <div class="connected-heading">
                        <span>Connected</span>
                        <span>Intelligence</span>
                        <span>for Every Scan.</span>
                    </div>
                    <p class="connected-description">
                        Every scan counts. Our smart system connects your inventory data in real-time — making tracking faster,
                        smarter, and fully automated.
                    </p>
                </div>
                <div class="connected-right" data-speed="0.3">
                    <div class="connected-card">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 130">
                            <rect x="20" y="20" width="160" height="90" rx="22" fill="#ffffff"/>
                            <rect x="55" y="45" width="20" height="40" fill="#15803d" rx="3"/>
                            <rect x="80" y="45" width="20" height="40" fill="#16a34a" rx="3"/>
                            <rect x="105" y="45" width="20" height="40" fill="#16a34a" rx="3"/>
                            <rect x="130" y="45" width="20" height="40" fill="#15803d" rx="3"/>
                            <rect x="45" y="40" width="110" height="8" rx="4" fill="#bbf7d0"/>
                            <rect x="45" y="87" width="110" height="8" rx="4" fill="#bbf7d0"/>
                        </svg>
                    </div>
                    <div class="connected-card">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 130">
                            <rect x="20" y="20" width="160" height="90" rx="22" fill="#ffffff"/>
                            <rect x="60" y="40" width="80" height="50" rx="12" fill="#e0f2fe"/>
                            <rect x="70" y="50" width="60" height="20" rx="6" fill="#ef4444"/>
                            <rect x="75" y="75" width="50" height="8" rx="4" fill="#94a3b8"/>
                            <path d="M115 60 L140 70 L125 85" fill="none" stroke="#8b5cf6" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
            </div>
        </section>
    </div>

  <script>
        let animationComplete = false;
        let scrollCount = 0;
        let lastScrollTop = 0;
        let parallaxActivated = false;
        let textRevealed = false;
        let visionRevealed = false;
        let connectedRevealed = false;
        
        // Wait for animation to complete
        setTimeout(() => {
            animationComplete = true;
            // Turn the splash into the first scrollable section
            document.getElementById('splash').classList.add('animation-complete');
            document.getElementById('mainContent').classList.add('active');
            document.body.classList.remove('no-scroll');

            // Show scroll indicator
            document.getElementById('scrollIndicator').classList.add('visible');
        }, 3500);

        // Parallax scrolling effect
        window.addEventListener('scroll', () => {
            if (!animationComplete) return;
            
            const scrolled = window.pageYOffset;
            const splash = document.getElementById('splash');
            const parallaxElements = document.querySelectorAll('[data-speed]');
            const visionSection = document.getElementById('visionSection');
            const visionLines = document.querySelectorAll('.vision-text-line');
            const visionCards = document.querySelectorAll('.vision-card');
            const connectedSection = document.getElementById('connectedSection');
            const connectedHeading = connectedSection ? connectedSection.querySelector('.connected-heading') : null;
            const connectedDescription = connectedSection ? connectedSection.querySelector('.connected-description') : null;
            const connectedCards = connectedSection ? connectedSection.querySelectorAll('.connected-card') : [];
            const scrollIndicator = document.getElementById('scrollIndicator');
            const scrollCountDisplay = document.getElementById('scrollCount');
            
            // Count scroll actions (detect scroll direction change or significant movement)
             if (!parallaxActivated) {
                 if (Math.abs(scrolled - lastScrollTop) > 100) {
                    scrollCount++;
                    lastScrollTop = scrolled;
                    scrollCountDisplay.textContent = Math.min(scrollCount, 5);
                    console.log('Scroll count:', scrollCount);
                }
                
                // Activate parallax after 5 scrolls
                if (scrollCount >= 5) {
                    parallaxActivated = true;
                    scrollIndicator.classList.remove('visible');
                    console.log('Parallax activated!');
                }
            }
            
             // Only proceed if parallax is activated
             if (parallaxActivated) {
                 // Fade out or show the splash depending on scroll position
                 if (scrolled > window.innerHeight * 0.3) {
                splash.classList.add('hidden');
            } else {
                splash.classList.remove('hidden');
            }
            
                 // Parallax effect for elements
                parallaxElements.forEach(element => {
                    const speed = element.getAttribute('data-speed');
                     const yPos = -(scrolled * speed);
                    element.style.transform = `translateY(${yPos}px)`;
                });

                 // Show elements as soon as parallax is active
                document.querySelectorAll('.shape').forEach(shape => {
                    shape.classList.add('visible');
                });
                
                document.querySelectorAll('.dots-grid').forEach(dots => {
                    dots.classList.add('visible');
                });
                
                document.querySelector('.geometric-shapes').classList.add('visible');
                
                document.querySelectorAll('.card').forEach(card => {
                    card.classList.add('visible');
                });
                
                document.querySelector('.chart-card').classList.add('visible');

                 // Reveal the text only when its section scrolls into view
                 if (!textRevealed) {
                     const heroText = document.getElementById('heroText');
                     if (heroText) {
                         const rect = heroText.getBoundingClientRect();
                         const triggerPoint = window.innerHeight * 0.75;
                         if (rect.top <= triggerPoint) {
                             textRevealed = true;
                document.querySelectorAll('.text-line').forEach(line => {
                    line.classList.add('visible');
                });
                         }
                     }
                 }

                 // Reveal vision-section text/cards when that section is in view
                 if (!visionRevealed && visionSection) {
                     const rectVision = visionSection.getBoundingClientRect();
                     if (rectVision.top <= window.innerHeight * 0.8) {
                         visionRevealed = true;
                         visionLines.forEach((line, idx) => {
                             setTimeout(() => line.classList.add('visible'), idx * 120);
                         });
                         visionCards.forEach((card, idx) => {
                             setTimeout(() => card.classList.add('visible'), 150 * idx);
                         });
                     }
                 }

                 // Reveal connected-section contents
                 if (!connectedRevealed && connectedSection) {
                     const rectConn = connectedSection.getBoundingClientRect();
                     if (rectConn.top <= window.innerHeight * 0.8) {
                         connectedRevealed = true;
                         if (connectedHeading) connectedHeading.classList.add('visible');
                         if (connectedDescription) connectedDescription.classList.add('visible');
                         connectedCards.forEach((card, idx) => {
                             setTimeout(() => card.classList.add('visible'), idx * 180);
                         });
                     }
                 }
            }
        });
    </script>
</body>
</html>