@extends('layouts.app')

@php 
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;

if (!function_exists('getBarcodeBase64')) {
    function getBarcodeBase64($barcode, $type = 'C128', $width = 2.0, $height = 50) {
        try {
            if (empty($barcode) || !is_string($barcode)) {
                return null;
            }
            if (!class_exists('Milon\Barcode\Facades\DNS1DFacade')) {
                return null;
            }

            $pngData = \Milon\Barcode\Facades\DNS1DFacade::getBarcodePNG($barcode, $type, (float) $width, (int) $height);
            if ($pngData && strlen($pngData) > 0) {
                $decoded = base64_decode($pngData, true);
                if ($decoded === false) {
                    return 'data:image/png;base64,' . base64_encode($pngData);
                }
                return 'data:image/png;base64,' . $pngData;
            }

            $svg = \Milon\Barcode\Facades\DNS1DFacade::getBarcodeSVG($barcode, $type, (float) $width, (int) $height);
            if (!empty($svg)) {
                return 'data:image/svg+xml;base64,' . base64_encode($svg);
            }
        } catch (\Throwable $e) {
            return null;
        }
        return null;
    }
}
@endphp

@push('styles')
<style>
/* New style for the page title */
    .page-title {
        text-align: center;
        font-size: 22px;
        color: #2c3e50;
        margin-bottom: 20px;
        font-weight: 700;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
    }

    .scan-container {
        max-width: 500px;
        margin: 0 auto;
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
    }

    .scan-container h2 {
        display: none; /* Hide the old H2 inside scan-container */
    }

    .form-group {
        display: flex;
        gap: 8px;
        margin-bottom: 15px;
    }

    input[type="text"] {
        flex: 1;
        padding: 12px 15px;
        font-size: 16px;
        border: 1.5px solid #ddd;
        border-radius: 8px;
        transition: border-color 0.3s ease;
    }

    input[type="text"]:focus {
        outline: none;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
    }

    /* Scanner input indicator */
    input[type="text"].scanner-input {
        border-color: #28a745;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
    }

    button {
        padding: 12px 20px;
        background: #0d6efd;
        border: none;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        border-radius: 8px;
        transition: background 0.3s ease;
    }

    button:hover {
        background: #0b5ed7;
    }

    .result h3 {
        margin-bottom: 12px;
        font-size: 16px;
        color: #495057;
    }

    /* Exit button styling */
    .exit-button {
        background: #28a745;
        margin-top: 20px;
        width: 70%;
        padding: 15px;
        font-size: 16px;
        font-weight: 600;
        text-align: center;
        display: block;
        text-decoration: none;
        color: white;
        border-radius: 8px;
        transition: background 0.3s ease;
        margin-left: auto; /* Center the button */
        margin-right: auto; /* Center the button */
    }

    .exit-button:hover {
        background: #218838;
        text-decoration: none;
        color: white;
    }

    .exit-button:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.25);
    }

    /* Full Set Container */
    .full-set-container {
        border: 2px solid #0d6efd;
        border-radius: 8px;
        margin-bottom: 15px;
        overflow: hidden;
        background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
    }

    .full-set-header {
        background: #0d6efd;
        color: white;
        padding: 10px 15px;
        font-size: 15px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .full-set-items {
        padding: 12px;
    }

    .full-set-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 15px;
    }

    /* Individual Item Box */
    .item-box {
        border: 1px solid #e3e3e3;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 12px;
        background: #fcfcfc;
        display: flex;
        gap: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        transition: box-shadow 0.3s ease;
    }

    .item-box:hover {
        box-shadow: 0 6px 14px rgba(0,0,0,0.07);
    }

    /* Full Set Item Box */
    .full-set-item-box {
        border: 1px solid #d1ecf1;
        padding: 10px;
        border-radius: 6px;
        background: white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        transition: transform 0.2s ease;
    }

    .full-set-item-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .photo-wrapper {
        width: 100px;
        height: 100px;
        background: #e9ecef;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .photo-wrapper-small {
        width: 50px;
        height: 50px;
        background: #e9ecef;
        border-radius: 6px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .photo-wrapper img, .photo-wrapper-small img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }

    .photo-wrapper .fa-image, .photo-wrapper-small .fa-image {
        font-size: 36px;
        color: #adb5bd;
    }

    .photo-wrapper-small .fa-image {
        font-size: 20px;
    }

    .item-info {
        flex-grow: 1;
    }

    .room-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 6px;
        color: #212529;
    }

    .full-set-item-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #0d6efd;
    }

    .label {
        font-weight: 600;
        color: #495057;
    }

    .item-info div, .full-set-item-info div {
        margin-bottom: 6px;
        font-size: 15px;
    }

    .full-set-item-info div {
        font-size: 13px;
    }

    .barcode-image {
        margin-top: 12px;
    }

    .barcode-image-small {
        margin-top: 8px;
    }

    .barcode-text {
        font-family: monospace;
        font-size: 13px;
        color: #6c757d;
        margin-top: 5px;
    }

    .barcode-text-small {
        font-family: monospace;
        font-size: 11px;
        color: #6c757d;
        margin-top: 3px;
    }

    .status {
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 13px;
    }

    .status-small {
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 15px;
        font-size: 11px;
    }

    .status.Usable, .status-small.Usable {
        background: #d4edda;
        color: #155724;
    }

    .status.Unusable, .status-small.Unusable {
        background: #f8d7da;
        color: #721c24;
    }

    .status.Borrowed, .status-small.Borrowed {
        background: #fff3cd;
        color: #856404;
    }

    .not-found {
        color: #dc3545;
        text-align: center;
        font-size: 18px;
        margin-top: 25px;
        font-weight: 500;
    }

    .back-link {
        display: block;
        margin-top: 30px;
        text-align: center;
        text-decoration: none;
        color: #0d6efd;
        font-weight: 500;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .set-summary {
        background: rgba(255, 255, 255, 0.8);
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 10px;
        border-left: 4px solid #0d6efd;
    }

    .set-summary h4 {
        margin: 0 0 8px 0;
        color: #0d6efd;
        font-size: 14px;
    }

    .set-meta {
        display: flex;
        gap: 20px;
        font-size: 14px;
        color: #6c757d;
    }

    .component-count {
        background: #0d6efd;
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .full-set-item-flex {
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    /* Main content area styling to work with the sidebar */
    .main-content {
        flex: 1;
        padding: 15px;
        background: #f4f6f9;
        overflow-y: auto;
    }

    /* Scanner status indicator */
    .scanner-status {
        text-align: center;
        margin-bottom: 15px;
        padding: 8px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        display: none;
    }

    .scanner-status.active {
        display: block;
        background: #d4edda;
        color: #155724;
    }

    .scanner-status.scanning {
        display: block;
        background: #fff3cd;
        color: #856404;
    }

    /* Camera Scanner Button */
    .camera-scanner-btn {
        width: 100%;
        padding: 15px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        border-radius: 10px;
        margin-top: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .camera-scanner-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }

    .camera-scanner-btn:active {
        transform: translateY(0);
    }

    /* Full Page Camera Scanner */
    .camera-scanner-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #000;
        z-index: 10000;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .camera-scanner-overlay.active {
        display: flex;
    }

    .camera-scanner-container {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    #camera-video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .scanner-viewfinder {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80%;
        max-width: 400px;
        aspect-ratio: 1;
        border: 3px solid #fff;
        border-radius: 20px;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
        pointer-events: none;
    }

    .scanner-line {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, transparent, #00ff00, transparent);
        animation: scanLine 2s linear infinite;
        box-shadow: 0 0 10px #00ff00;
    }

    @keyframes scanLine {
        0% {
            top: 0;
        }
        100% {
            top: calc(100% - 3px);
        }
    }

    .scanner-corners {
        position: absolute;
        width: 100%;
        height: 100%;
    }

    .scanner-corner {
        position: absolute;
        width: 30px;
        height: 30px;
        border: 4px solid #00ff00;
    }

    .scanner-corner.top-left {
        top: -4px;
        left: -4px;
        border-right: none;
        border-bottom: none;
    }

    .scanner-corner.top-right {
        top: -4px;
        right: -4px;
        border-left: none;
        border-bottom: none;
    }

    .scanner-corner.bottom-left {
        bottom: -4px;
        left: -4px;
        border-right: none;
        border-top: none;
    }

    .scanner-corner.bottom-right {
        bottom: -4px;
        right: -4px;
        border-left: none;
        border-top: none;
    }

    .scanner-instructions {
        position: absolute;
        bottom: 100px;
        left: 50%;
        transform: translateX(-50%);
        color: white;
        text-align: center;
        font-size: 18px;
        background: rgba(0, 0, 0, 0.7);
        padding: 15px 25px;
        border-radius: 10px;
        z-index: 10001;
    }

    .scanner-controls {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 15px;
        z-index: 10001;
    }

    .scanner-btn {
        padding: 15px 30px;
        border: none;
        border-radius: 50px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .scanner-btn-close {
        background: #dc3545;
        color: white;
    }

    .scanner-btn-close:hover {
        background: #c82333;
        transform: scale(1.05);
    }

    /* Scanner Result Modal (Inside Camera) */
    .scanner-result-modal {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 10002;
        padding: 20px;
        overflow-y: auto;
    }

    .scanner-result-modal.active {
        display: flex;
    }

    .scanner-result-content {
        background: white;
        border-radius: 20px;
        padding: 30px;
        max-width: 90%;
        max-height: 90%;
        overflow-y: auto;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }

    .scanner-result-header {
        text-align: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e9ecef;
    }

    .scanner-result-header h3 {
        color: #28a745;
        font-size: 24px;
        margin-bottom: 10px;
    }

    .scanner-result-barcode {
        text-align: center;
        font-size: 20px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 20px;
        font-family: monospace;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .scanner-result-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 25px;
    }

    .scanner-result-btn {
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .scanner-result-btn-scan-again {
        background: #0d6efd;
        color: white;
    }

    .scanner-result-btn-scan-again:hover {
        background: #0b5ed7;
        transform: scale(1.05);
    }

    .scanner-result-btn-close {
        background: #6c757d;
        color: white;
    }

    .scanner-result-btn-close:hover {
        background: #5a6268;
        transform: scale(1.05);
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .page-title {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .scan-container {
            padding: 20px;
            margin: 10px;
        }

        .form-group {
            flex-direction: column;
        }

        input[type="text"] {
            width: 100%;
        }

        button {
            width: 100%;
        }

        .camera-scanner-btn {
            font-size: 16px;
            padding: 12px 15px;
        }

        .scanner-viewfinder {
            width: 90%;
        }

        .scanner-instructions {
            font-size: 14px;
            padding: 10px 15px;
            bottom: 80px;
        }

        .scanner-controls {
            flex-direction: column;
            width: 90%;
            bottom: 10px;
        }

        .scanner-btn {
            width: 100%;
        }

        .scanner-result-content {
            padding: 20px;
            max-width: 95%;
        }

        .scanner-result-header h3 {
            font-size: 20px;
        }

        .scanner-result-barcode {
            font-size: 16px;
        }

        .scanner-result-actions {
            flex-direction: column;
        }

        .scanner-result-btn {
            width: 100%;
        }

        .item-box {
            flex-direction: column;
        }

        .photo-wrapper {
            width: 100%;
            height: 200px;
        }

        .full-set-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .main-content {
            padding: 10px;
        }

        .scan-container {
            padding: 15px;
        }

        .page-title {
            font-size: 20px;
        }

        .scanner-viewfinder {
            width: 95%;
        }

        .scanner-instructions {
            font-size: 12px;
            padding: 8px 12px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const barcodeInput = document.querySelector('input[name="barcode"]');
    const form = document.querySelector('form');
    const scannerStatus = document.querySelector('.scanner-status');
    
    let inputStartTime = null;
    let inputBuffer = '';
    let inputTimeout = null;
    
    // Configuration for scanner detection
    const SCANNER_CONFIG = {
        minLength: 3,           
        maxLength: 50,          
        maxInputTime: 100,      
        submitDelay: 50,        
        enterKeyDetection: true 
    };
    
    // Function to detect if input is from scanner
    function isScannerInput(inputTime, inputLength) {
        return inputTime <= SCANNER_CONFIG.maxInputTime && 
               inputLength >= SCANNER_CONFIG.minLength && 
               inputLength <= SCANNER_CONFIG.maxLength;
    }
    
    // Function to show scanner status
    function showScannerStatus(message, className) {
        if (scannerStatus) {
            scannerStatus.textContent = message;
            scannerStatus.className = 'scanner-status ' + className;
            setTimeout(() => {
                scannerStatus.className = 'scanner-status';
            }, 2000);
        }
    }
    
    // Function to submit form with scanner styling
    function submitWithScannerEffect() {
        barcodeInput.classList.add('scanner-input');
        showScannerStatus('Barcode scanned successfully!', 'active');
        
        setTimeout(() => {
            form.submit();
        }, SCANNER_CONFIG.submitDelay);
    }
    
    barcodeInput.addEventListener('input', function(e) {
        const currentTime = Date.now();
        
        if (this.value.length === 1) {
            inputStartTime = currentTime;
            inputBuffer = this.value;
        } else {
            inputBuffer = this.value;
        }
        
        if (inputTimeout) {
            clearTimeout(inputTimeout);
        }
        
        inputTimeout = setTimeout(() => {
            if (inputStartTime && inputBuffer.length >= SCANNER_CONFIG.minLength) {
                const inputTime = currentTime - inputStartTime;
                
                if (isScannerInput(inputTime, inputBuffer.length)) {
                    submitWithScannerEffect();
                }
            }
        }, SCANNER_CONFIG.maxInputTime);
    });
    
    barcodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && SCANNER_CONFIG.enterKeyDetection) {
            e.preventDefault();
            
            const currentTime = Date.now();
            const inputValue = this.value.trim();
            
            if (inputValue.length >= SCANNER_CONFIG.minLength) {
                if (inputStartTime) {
                    const inputTime = currentTime - inputStartTime;
                    if (isScannerInput(inputTime, inputValue.length)) {
                        submitWithScannerEffect();
                        return;
                    }
                }
                
                showScannerStatus('Processing barcode...', 'scanning');
                setTimeout(() => {
                    form.submit();
                }, SCANNER_CONFIG.submitDelay);
            }
        }
    });
    
    barcodeInput.addEventListener('keydown', function(e) {
        if (e.key.length === 1) {
            const currentTime = Date.now();
            if (inputStartTime && currentTime - inputStartTime > 1000) {
                inputStartTime = currentTime;
            }
        }
    });
    
    barcodeInput.addEventListener('paste', function(e) {
        setTimeout(() => {
            const pastedValue = this.value.trim();
            if (pastedValue.length >= SCANNER_CONFIG.minLength && 
                pastedValue.length <= SCANNER_CONFIG.maxLength) {
                showScannerStatus('Barcode pasted - processing...', 'scanning');
                setTimeout(() => {
                    form.submit();
                }, SCANNER_CONFIG.submitDelay);
            }
        }, 10);
    });
    
    barcodeInput.addEventListener('blur', function() {
        setTimeout(() => {
            this.classList.remove('scanner-input');
        }, 1000);
    });
    
    // Auto-focus on input when page loads
    barcodeInput.focus();
});

// Camera Barcode Scanner
document.addEventListener('DOMContentLoaded', function() {
    const cameraScannerBtn = document.getElementById('cameraScannerBtn');
    const cameraScannerOverlay = document.getElementById('cameraScannerOverlay');
    const closeScannerBtn = document.getElementById('closeScannerBtn');
    const cameraVideo = document.getElementById('camera-video');
    const cameraCanvas = document.getElementById('camera-canvas');
    const scannerResultModal = document.getElementById('scannerResultModal');
    const scannerResultContent = document.getElementById('scannerResultContent');
    const scannerInstructions = document.getElementById('scannerInstructions');
    
    let stream = null;
    let scanning = false;
    let scanInterval = null;
    let barcodeDetector = null;
    
    // Check if BarcodeDetector API is available
    const hasBarcodeDetector = 'BarcodeDetector' in window;
    
    // Initialize BarcodeDetector if available
    if (hasBarcodeDetector) {
        try {
            barcodeDetector = new BarcodeDetector({
                formats: ['code_128', 'ean_13', 'ean_8', 'code_39', 'code_93', 'codabar', 'upc_a', 'upc_e', 'qr_code']
            });
        } catch (e) {
            console.warn('BarcodeDetector initialization failed:', e);
            barcodeDetector = null;
        }
    }
    
    // Load ZXing library as fallback
    let ZXing = null;
    if (!hasBarcodeDetector || !barcodeDetector) {
        const script = document.createElement('script');
        script.src = 'https://unpkg.com/@zxing/library@latest';
        script.onload = function() {
            ZXing = window.ZXing;
        };
        document.head.appendChild(script);
    }
    
    // Open camera scanner
    cameraScannerBtn.addEventListener('click', async function() {
        try {
            scannerInstructions.textContent = 'Requesting camera access...';
            cameraScannerOverlay.classList.add('active');
            
            // Request camera access
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'environment', // Use back camera on mobile
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }
            });
            
            cameraVideo.srcObject = stream;
            scannerInstructions.textContent = 'Position the barcode within the frame';
            
            // Start scanning after video is ready
            cameraVideo.addEventListener('loadedmetadata', function() {
                cameraCanvas.width = cameraVideo.videoWidth;
                cameraCanvas.height = cameraVideo.videoHeight;
                startScanning();
            });
            
        } catch (error) {
            console.error('Error accessing camera:', error);
            scannerInstructions.textContent = 'Camera access denied. Please allow camera access and try again.';
            setTimeout(() => {
                closeScanner();
            }, 3000);
        }
    });
    
    // Close scanner
    function closeScanner() {
        if (scanInterval) {
            clearInterval(scanInterval);
            scanInterval = null;
        }
        
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        
        cameraVideo.srcObject = null;
        cameraScannerOverlay.classList.remove('active');
        scannerResultModal.classList.remove('active');
        scanning = false;
    }
    
    closeScannerBtn.addEventListener('click', closeScanner);
    
    // Start scanning
    function startScanning() {
        if (scanning) return;
        scanning = true;
        
        scanInterval = setInterval(async () => {
            if (cameraVideo.readyState === cameraVideo.HAVE_ENOUGH_DATA) {
                await scanBarcode();
            }
        }, 500); // Scan every 500ms
    }
    
    // Scan for barcode
    async function scanBarcode() {
        try {
            const ctx = cameraCanvas.getContext('2d');
            ctx.drawImage(cameraVideo, 0, 0, cameraCanvas.width, cameraCanvas.height);
            
            let result = null;
            
            // Try BarcodeDetector API first
            if (barcodeDetector) {
                try {
                    const barcodes = await barcodeDetector.detect(cameraCanvas);
                    if (barcodes && barcodes.length > 0) {
                        result = barcodes[0].rawValue;
                    }
                } catch (e) {
                    console.warn('BarcodeDetector error:', e);
                }
            }
            
            // Fallback to ZXing if BarcodeDetector didn't work
            if (!result && ZXing) {
                try {
                    const codeReader = new ZXing.BrowserMultiFormatReader();
                    const imageData = ctx.getImageData(0, 0, cameraCanvas.width, cameraCanvas.height);
                    const result2 = await codeReader.decodeFromImageData(imageData);
                    if (result2 && result2.text) {
                        result = result2.text;
                    }
                } catch (e) {
                    // No barcode found, continue scanning
                }
            }
            
            // If barcode found, process it
            if (result) {
                clearInterval(scanInterval);
                scanning = false;
                processBarcodeResult(result);
            }
            
        } catch (error) {
            console.error('Scan error:', error);
        }
    }
    
    // Process barcode result
    async function processBarcodeResult(barcode) {
        scannerInstructions.textContent = 'Barcode detected! Processing...';
        
        try {
            // Call API to search for barcode
            const response = await fetch('{{ route("roomitem.scan.api-search") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ barcode: barcode })
            });
            
            const data = await response.json();
            displayScanResult(barcode, data);
            
        } catch (error) {
            console.error('Error processing barcode:', error);
            displayScanResult(barcode, {
                success: false,
                message: 'Error processing barcode. Please try again.'
            });
        }
    }
    
    // Display scan result
    function displayScanResult(barcode, data) {
        let html = '';
        
        if (data.success && data.items && data.items.length > 0) {
            html += '<div class="scanner-result-header">';
            html += '<h3><i class="fas fa-check-circle"></i> Barcode Found!</h3>';
            html += '<div class="scanner-result-barcode">' + barcode + '</div>';
            html += '</div>';
            
            html += '<div class="result">';
            data.items.forEach((item, index) => {
                html += '<div class="item-box" style="margin-bottom: 15px;">';
                html += '<div class="item-info">';
                html += '<div class="room-title">' + (item.room_title || 'N/A') + '</div>';
                html += '<div><span class="label">Category:</span> ' + (item.device_category || 'N/A') + '</div>';
                html += '<div><span class="label">Type:</span> ' + (item.device_type || 'Unspecified') + '</div>';
                html += '<div><span class="label">Brand:</span> ' + (item.brand || 'N/A') + '</div>';
                html += '<div><span class="label">Model:</span> ' + (item.model || 'N/A') + '</div>';
                html += '<div><span class="label">Serial Number:</span> ' + (item.serial_number || 'N/A') + '</div>';
                if (item.description) {
                    html += '<div><span class="label">Description:</span> ' + item.description + '</div>';
                }
                html += '<div><span class="label">Status:</span> <span class="status ' + item.status + '">' + item.status + '</span></div>';
                html += '</div>';
                html += '</div>';
            });
            html += '</div>';
        } else {
            html += '<div class="scanner-result-header">';
            html += '<h3 style="color: #dc3545;"><i class="fas fa-times-circle"></i> Not Found</h3>';
            html += '<div class="scanner-result-barcode">' + barcode + '</div>';
            html += '</div>';
            html += '<div class="not-found" style="text-align: center; padding: 20px;">';
            html += '<p>' + (data.message || 'No item found for this barcode') + '</p>';
            html += '</div>';
        }
        
        html += '<div class="scanner-result-actions">';
        html += '<button class="scanner-result-btn scanner-result-btn-scan-again" id="scanAgainBtn">';
        html += '<i class="fas fa-redo"></i> Scan Again';
        html += '</button>';
        html += '<button class="scanner-result-btn scanner-result-btn-close" id="closeResultBtn">';
        html += '<i class="fas fa-times"></i> Close';
        html += '</button>';
        html += '</div>';
        
        scannerResultContent.innerHTML = html;
        scannerResultModal.classList.add('active');
        
        // Add event listeners for result buttons
        document.getElementById('scanAgainBtn').addEventListener('click', function() {
            scannerResultModal.classList.remove('active');
            scannerInstructions.textContent = 'Position the barcode within the frame';
            startScanning();
        });
        
        document.getElementById('closeResultBtn').addEventListener('click', function() {
            closeScanner();
        });
    }
    
    // Handle image scanning from room-manage.blade.php
    // This allows clicking on barcode images to scan them
    // This will work when the scan-barcode page is loaded
    if (window.location.pathname.includes('scan-barcode')) {
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('clickable-barcode') || 
                e.target.classList.contains('barcode-display-img') || 
                (e.target.tagName === 'IMG' && e.target.closest('.bwippbarcode'))) {
                // Extract barcode from image alt, data attribute, or nearby text
                let barcode = e.target.getAttribute('data-barcode') || 
                             e.target.alt || 
                             (e.target.closest('.barcode-wrapper') ? 
                              e.target.closest('.barcode-wrapper').querySelector('.barcode-text')?.textContent?.trim() : null);
                
                if (barcode && barcode !== 'Barcode' && barcode !== '' && barcode.length >= 3) {
                    // Set the barcode in the input and submit
                    const barcodeInput = document.querySelector('input[name="barcode"]');
                    if (barcodeInput) {
                        barcodeInput.value = barcode;
                        barcodeInput.closest('form').submit();
                    }
                }
            }
        });
    }
});
</script>
@endpush

@section('title', 'Scan Barcode')

@section('content')
<div class="main-content">
    <h1 class="page-title"><i class="fas fa-barcode"></i> Scan Barcode</h1>

    <div class="scan-container">
        <div class="scanner-status"></div>
        
        <form method="POST" action="{{ route('roomitem.scan.search') }}">
            @csrf
            <div class="form-group">
                <input type="text" name="barcode" placeholder="Enter or scan barcode..." value="{{ old('barcode', $barcode ?? '') }}" required autofocus>
                <button type="submit"><i class="fas fa-search"></i> Search</button>
            </div>
        </form>

        <button type="button" class="camera-scanner-btn" id="cameraScannerBtn">
            <i class="fas fa-camera"></i> Open Camera Scanner
        </button>

        @if(isset($error) && $error)
            <div class="not-found" style="color: #dc3545;">
                ⚠️ {{ $error }}
            </div>
        @elseif(isset($scanned) && $scanned && isset($items) && count($items) > 0)
            <div class="result">
                <h3>🔍 Result for Barcode: <code>{{ $barcode }}</code></h3>

                @php
                    // Group items by set ID if they belong to a full set
                    $fullSets = [];
                    $individualItems = [];
                    
                    foreach($items as $item) {
                        // Check if item is part of a full set by looking for set pattern in serial number
                        if (preg_match('/^(PC|Monitor|Keyboard|Mouse|PSU)(\d+)$/i', $item->serial_number, $matches)) {
                            $setId = $matches[2];
                            if (!isset($fullSets[$setId])) {
                                $fullSets[$setId] = [];
                            }
                            $fullSets[$setId][] = $item;
                        } else {
                            $individualItems[] = $item;
                        }
                    }
                @endphp

                {{-- Display Full Sets --}}
                @foreach($fullSets as $setId => $setItems)
                    <div class="full-set-container">
                        <div class="full-set-header">
                            <i class="fas fa-desktop"></i>
                            PC{{ str_pad($setId, 3, '0', STR_PAD_LEFT) }}
                            <span class="component-count">{{ count($setItems) }} Components</span>
                        </div>
                        <div class="full-set-items">
                            <div class="set-summary">
                                <h4>PC Information</h4>
                                <div class="set-meta">
                                    <div><strong>PC#:</strong> PC{{ str_pad($setId, 3, '0', STR_PAD_LEFT) }}</div>
                                    <div><strong>Room:</strong> {{ $setItems[0]->room_title }}</div>
                                    <div><strong>Brand:</strong> {{ $setItems[0]->brand ?? 'N/A' }}</div>
                                    <div><strong>Model:</strong> {{ $setItems[0]->model ?? 'N/A' }}</div>
                                    <div><strong>Set ID:</strong> {{ $setId }}</div>
                                </div>
                            </div>
                            
                            <div class="full-set-grid">
                                @foreach($setItems as $item)
                                    <div class="full-set-item-box">
                                        <div class="full-set-item-flex">
                                            <div class="photo-wrapper-small">
                                                @if($item->photo)
                                                    <img src="{{ route('room-item.photo', $item->id) }}" alt="Item Photo">
                                                @else
                                                    <i class="fas fa-image"></i>
                                                @endif
                                            </div>
                                            <div class="full-set-item-info">
                                                <div class="full-set-item-title">
                                                    @if(str_contains($item->serial_number, 'PC'))
                                                        <i class="fas fa-desktop"></i> System Unit
                                                    @elseif(str_contains($item->serial_number, 'Monitor'))
                                                        <i class="fas fa-tv"></i> Monitor
                                                    @elseif(str_contains($item->serial_number, 'Keyboard'))
                                                        <i class="fas fa-keyboard"></i> Keyboard
                                                    @elseif(str_contains($item->serial_number, 'Mouse'))
                                                        <i class="fas fa-mouse"></i> Mouse
                                                    @elseif(str_contains($item->serial_number, 'PSU'))
                                                        <i class="fas fa-plug"></i> Power Supply
                                                    @else
                                                        <i class="fas fa-cog"></i> {{ $item->device_category }}
                                                    @endif
                                                </div>
                                                <div><span class="label">Serial:</span> {{ $item->serial_number }}</div>
                                                <div><span class="label">Category:</span> {{ $item->device_category }}</div>
                                                @if($item->description)
                                                    <div><span class="label">Description:</span> {{ Str::limit($item->description, 50) }}</div>
                                                @endif
                                                <div>
                                                    <span class="label">Status:</span>
                                                    <span class="status-small {{ $item->status }}">{{ $item->status }}</span>
                                                </div>
                                                <div class="barcode-image-small">
                                                    @if($item->barcode)
                                                        @php
                                                            $barcodeImage = getBarcodeBase64($item->barcode, 'C128', 1.5, 40);
                                                        @endphp
                                                        @if($barcodeImage)
                                                            <img src="{{ $barcodeImage }}" alt="Barcode">
                                                        @endif
                                                        <div class="barcode-text-small">{{ $item->barcode }}</div>
                                                    @else
                                                        <div class="barcode-text-small" style="color: #999;">No barcode</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Display Individual Items --}}
                @foreach($individualItems as $item)
                    @php
                        // Try to extract PC number from barcode or serial number for individual items
                        $pcNumber = null;
                        if (preg_match('/(\d{3})$/', $item->barcode, $matches)) {
                            $pcNumber = intval($matches[1]);
                        } elseif (preg_match('/(\d{3})$/', $item->serial_number, $matches)) {
                            $pcNumber = intval($matches[1]);
                        }
                    @endphp
                    <div class="item-box">
                        <div class="photo-wrapper">
                            @if($item->photo)
                                <img src="{{ route('room-item.photo', $item->id) }}" alt="Item Photo">
                            @else
                                <i class="fas fa-image"></i>
                            @endif
                        </div>
                        <div class="item-info">
                            <div class="room-title">
                                {{ $item->room_title }}
                                @if($pcNumber)
                                    <span style="color: #0d6efd; font-weight: 600; margin-left: 10px;">PC{{ str_pad($pcNumber, 3, '0', STR_PAD_LEFT) }}</span>
                                @endif
                            </div>

                            <div><span class="label">Category:</span> {{ $item->device_category }}</div>
                            <div><span class="label">Type:</span> {{ $item->device_type ?? 'Unspecified' }}</div>
                            <div><span class="label">Brand:</span> {{ $item->brand ?? 'N/A' }}</div>
                            <div><span class="label">Model:</span> {{ $item->model ?? 'N/A' }}</div>
                            <div><span class="label">Serial Number:</span> {{ $item->serial_number }}</div>
                            <div><span class="label">Description:</span> {{ $item->description }}</div>
                            <div>
                                <span class="label">Status:</span>
                                <span class="status {{ $item->status }}">{{ $item->status }}</span>
                            </div>
                            <div class="barcode-image">
                                @if($item->barcode)
                                    @php
                                        $barcodeImage = getBarcodeBase64($item->barcode, 'C128', 2, 60);
                                    @endphp
                                    @if($barcodeImage)
                                        <img src="{{ $barcodeImage }}" alt="Barcode">
                                    @endif
                                    <div class="barcode-text">{{ $item->barcode }}</div>
                                @else
                                    <div class="barcode-text" style="color: #999;">No barcode</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('roomitem.scan.index') }}" class="exit-button">
                <i class="fas fa-check"></i> Okay
            </a>

        @elseif(isset($notFound) && $notFound)
            <div class="not-found">
                ❌ No item found for barcode: <strong>{{ $barcode }}</strong>
            </div>
        @endif
        
    </div>
</div>

<!-- Camera Scanner Overlay -->
<div class="camera-scanner-overlay" id="cameraScannerOverlay">
    <div class="camera-scanner-container">
        <video id="camera-video" autoplay playsinline></video>
        <canvas id="camera-canvas" style="display: none;"></canvas>
        
        <div class="scanner-viewfinder">
            <div class="scanner-line"></div>
            <div class="scanner-corners">
                <div class="scanner-corner top-left"></div>
                <div class="scanner-corner top-right"></div>
                <div class="scanner-corner bottom-left"></div>
                <div class="scanner-corner bottom-right"></div>
            </div>
        </div>
        
        <div class="scanner-instructions" id="scannerInstructions">
            Position the barcode within the frame
        </div>
        
        <div class="scanner-controls">
            <button class="scanner-btn scanner-btn-close" id="closeScannerBtn">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    </div>
    
    <!-- Scanner Result Modal -->
    <div class="scanner-result-modal" id="scannerResultModal">
        <div class="scanner-result-content" id="scannerResultContent">
            <!-- Result content will be inserted here -->
        </div>
    </div>
</div>
@endsection