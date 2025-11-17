@extends('layouts.app')

@php use Milon\Barcode\Facades\DNS1DFacade as DNS1D; @endphp

@push('styles')
<style>
/* New style for the page title */
    .page-title {
        text-align: center;
        font-size: 36px; /* Larger font size for a main title */
        color: #2c3e50; /* A darker, more prominent color */
        margin-bottom: 40px; /* More space below the title */
        font-weight: 700; /* Bolder */
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05); /* Subtle shadow */
    }

    .scan-container {
        max-width: 500px;
        margin: 0 auto;
        background: #ffffff;
        padding: 35px 40px;
        border-radius: 14px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .scan-container h2 {
        display: none; /* Hide the old H2 inside scan-container */
    }

    .form-group {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
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
        margin-bottom: 20px;
        font-size: 20px;
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
        border-radius: 12px;
        margin-bottom: 25px;
        overflow: hidden;
        background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
    }

    .full-set-header {
        background: #0d6efd;
        color: white;
        padding: 15px 20px;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .full-set-items {
        padding: 20px;
    }

    .full-set-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 15px;
    }

    /* Individual Item Box */
    .item-box {
        border: 1px solid #e3e3e3;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        background: #fcfcfc;
        display: flex;
        gap: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        transition: box-shadow 0.3s ease;
    }

    .item-box:hover {
        box-shadow: 0 6px 14px rgba(0,0,0,0.07);
    }

    /* Full Set Item Box */
    .full-set-item-box {
        border: 1px solid #d1ecf1;
        padding: 15px;
        border-radius: 8px;
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
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 8px;
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
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        border-left: 4px solid #0d6efd;
    }

    .set-summary h4 {
        margin: 0 0 10px 0;
        color: #0d6efd;
        font-size: 16px;
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
        padding: 30px;
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

        @if(isset($scanned) && $scanned && isset($items) && count($items) > 0)
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
                                                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item->barcode, 'C128', 1.5, 40) }}" alt="Barcode">
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
                                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item->barcode, 'C128', 2, 60) }}" alt="Barcode">
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
@endsection