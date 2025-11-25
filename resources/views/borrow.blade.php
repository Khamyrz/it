@extends('layouts.app')
@section('title', 'Borrow')
@section('content')
@push('styles')
    <!-- Leaflet CSS 2025 -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder@1.13.0/dist/Control.Geocoder.css" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        * {
    
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
 .main-content {
    flex: 1;
    padding: 5px;
    background: #f5f5f5;
    overflow-y: auto;
}

        body {
        margin: 0;
        font-family: Arial, sans-serif;
        display: flex;
        height: 100vh;
        background: #f4f6f8;
    }
        /* Main content wrapper for internal spacing */
        .main-content {
            padding: clamp(15px, 4vw, 30px);
            padding-left: 0; /* Remove left padding for edge-to-edge */
            padding-right: 0; /* Remove right padding for edge-to-edge */
        }

        /* Content wrapper for internal elements */
        .content-wrapper {
            padding: 0 clamp(15px, 4vw, 30px); /* Add horizontal padding only to content */
        }

        /* Header Section */
        .page-header {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }

        .page-title {
            font-size: clamp(18px, 4vw, 22px);
            color: #2c3e50;
            margin: 0;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .page-title::before {
            content: "📋";
            font-size: clamp(16px, 3vw, 20px);
        }

        /* Top Buttons - Enhanced Responsive Design */
        .top-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        .top-buttons button, 
        .top-buttons a {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: clamp(10px, 2vw, 12px) clamp(15px, 3vw, 20px);
            text-decoration: none;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-size: clamp(13px, 2.5vw, 14px);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 10px rgba(44, 62, 80, 0.15);
            white-space: nowrap;
            min-width: fit-content;
        }

        .top-buttons button:hover, 
        .top-buttons a:hover {
            background: linear-gradient(135deg, #1b2733, #2c3e50);
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(44, 62, 80, 0.25);
        }

        .top-buttons button:active,
        .top-buttons a:active {
            transform: translateY(0);
        }

        /* Success Message */
        .success-message {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            padding: 15px 20px;
            border-radius: 10px;
            margin-top: 15px;
            margin-left: clamp(15px, 4vw, 30px);
            margin-right: clamp(15px, 4vw, 30px);
            border-left: 4px solid #28a745;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            box-shadow: 0 2px 15px rgba(40, 167, 69, 0.1);
        }
/* Page Header: center title */
.page-header {
    display: flex;
    flex-direction: column;
    align-items: center; /* Centers horizontally */
    text-align: center;  /* Centers text inside */
    gap: 15px;
    margin-bottom: 30px;
}


/* Top-buttons used at bottom: right-aligned */
.bottom-buttons {
    justify-content: flex-end;
    margin-top: 20px;
    flex-wrap: wrap;
    gap: 12px;
}

/* Make sure .top-buttons itself is flex */
.top-buttons {
    display: flex;
}

        /* Table Container - Enhanced Responsive */
     .table-container {
    background: #fff;
    border-radius: 0;
    overflow: auto;
    max-height: 70vh;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    width: 90%;
    margin: 0 auto;
    padding: 0;
    transform: translateX(3%);
}


        .table-responsive {
            max-height: 70vh;
            overflow-x: auto;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            min-width: 800px; /* Ensures table doesn't get too cramped */
        }

        th, td {
            padding: clamp(10px, 2vw, 16px);
            text-align: left;
            border: none;
            border-bottom: 1px solid #f1f3f4;
            vertical-align: middle;
        }

        th {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            font-weight: 600;
            font-size: clamp(12px, 2vw, 14px);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        td {
            font-size: clamp(13px, 2.2vw, 14px);
            color: #495057;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #f8f9fa;
            transition: background 0.3s ease;
        }

        /* Status Styling */
        .unusable {
            color: #dc3545;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-borrowed {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-usable {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        /* Button Styling */
        .btn-return {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
            min-width: fit-content;
        }

        .btn-return:hover:not(.btn-disabled) {
            background: linear-gradient(135deg, #218838, #1e7e34);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-disabled {
            background: #e9ecef !important;
            color: #6c757d !important;
            cursor: not-allowed !important;
            transform: none !important;
            box-shadow: none !important;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.6;
        }

        /* Modal Styles - Enhanced Responsive */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(3px);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: white;
            margin: clamp(2%, 5vh, 5%) auto;
            padding: clamp(20px, 5vw, 30px);
            border-radius: 15px;
            width: clamp(300px, 90vw, 700px);
            max-width: 95vw;
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { 
                transform: translateY(-50px);
                opacity: 0;
            }
            to { 
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal h3 {
            color: #2c3e50;
            margin: 0 0 25px 0;
            font-size: clamp(18px, 4vw, 22px);
            font-weight: 700;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 28px;
            cursor: pointer;
            color: #adb5bd;
            transition: color 0.3s ease;
            line-height: 1;
        }

        .close:hover {
            color: #495057;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
            font-size: 14px;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background: white;
            font-family: inherit;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .submit-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            width: 100%;
            justify-content: center;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #5a6fd8, #6a4190);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }

        /* Date and Time Formatting */
        .date-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .date-main {
            font-weight: 500;
        }

        .date-relative {
            font-size: 11px;
            color: #6c757d;
            font-style: italic;
        }

        /* Mobile Responsive Breakpoints */
        @media (max-width: 768px) {
            .page-header {
                text-align: center;
            }

            .top-buttons {
                justify-content: center;
            }

            .top-buttons button,
            .top-buttons a {
                flex: 1;
                min-width: 0;
                justify-content: center;
            }

            /* Card-based layout for very small screens */
            .table-responsive {
                display: none;
            }

            .card-layout {
                display: block;
                padding: 0 clamp(15px, 4vw, 30px);
            }

            .item-card {
                background: white;
                border-radius: 12px;
                padding: 20px;
                margin-bottom: 15px;
                box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
                border-left: 4px solid #667eea;
            }

            .card-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 15px;
                flex-wrap: wrap;
                gap: 10px;
            }

            .card-title {
                font-weight: 600;
                color: #2c3e50;
                font-size: 16px;
            }

            .card-status {
                font-size: 12px;
                padding: 4px 8px;
                border-radius: 12px;
                font-weight: 600;
            }

            .card-details {
                display: grid;
                gap: 10px;
                margin-bottom: 15px;
            }

            .card-detail {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 0;
                border-bottom: 1px solid #f1f3f4;
            }

            .card-detail:last-child {
                border-bottom: none;
            }

            .detail-label {
                font-weight: 600;
                color: #6c757d;
                font-size: 13px;
            }

            .detail-value {
                font-size: 14px;
                color: #495057;
                text-align: right;
            }

            .card-actions {
                display: flex;
                justify-content: flex-end;
                margin-top: 15px;
            }
        }

        @media (min-width: 769px) {
            .card-layout {
                display: none;
            }
        }

        /* Tablet optimizations */
        @media (min-width: 768px) and (max-width: 1024px) {
            .main-content {
                padding: 20px 0; /* Remove horizontal padding */
            }

            .content-wrapper {
                padding: 0 20px; /* Add horizontal padding only to content */
            }

            .modal-content {
                width: 85vw;
                max-width: 600px;
            }
        }

        /* Large screen optimizations */
        @media (min-width: 1200px) {
            .table-container {
                border-radius: 0; /* Keep edge-to-edge */
            }

            .modal-content {
                max-width: 800px;
            }
        }

        /* High DPI screen adjustments */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .top-buttons button,
            .top-buttons a {
                box-shadow: 0 1px 5px rgba(44, 62, 80, 0.2);
            }
        }

        /* Print styles */
        @media print {
            .top-buttons,
            .btn-return,
            .modal {
                display: none !important;
            }

            body {
                background: white;
                padding: 0;
            }

            .table-container {
                box-shadow: none;
            }
        }

        /* Custom scrollbar for webkit browsers */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Map Styles */
        #map, #trackerMap {
            height: 400px;
            width: 100%;
            border-radius: 10px;
            margin: 15px 0;
            z-index: 1;
        }

        .map-container {
            margin: 15px 0;
        }

        .setup-map-btn {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            margin-bottom: 10px;
        }

        .setup-map-btn:hover {
            background: linear-gradient(135deg, #138496, #117a8b);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
        }

        .photo-preview {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            margin-top: 10px;
            display: none;
        }

        .photo-preview.show {
            display: block;
        }

        .pc-group {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        .pc-group-header {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Dropdown styling for grouped items */
        select option.full-set-option {
            font-weight: 600;
            background-color: #e3f2fd;
            color: #1976d2;
        }

        select option[style*="padding-left"] {
            color: #666;
            font-size: 0.95em;
        }

        select optgroup {
            font-weight: 600;
            color: #2c3e50;
        }

        /* Custom Collapsible Dropdown */
        .custom-dropdown {
            position: relative;
            width: 100%;
        }

        .dropdown-toggle {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: border-color 0.3s ease;
        }

        .dropdown-toggle:hover {
            border-color: #667eea;
        }

        .dropdown-toggle:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            margin-top: 5px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f1f3f4;
            transition: background 0.2s ease;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
        }

        .dropdown-item.room-header {
            background: #e3f2fd;
            font-weight: 600;
            color: #1976d2;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dropdown-item.room-header:hover {
            background: #bbdefb;
        }

        .dropdown-item.pc-header {
            background: #f5f5f5;
            font-weight: 600;
            color: #666;
            padding-left: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dropdown-item.pc-header:hover {
            background: #eeeeee;
        }

        .dropdown-item.item-option {
            padding-left: 50px;
            color: #495057;
        }

        .dropdown-item.item-option.full-set-option {
            background: #e8f5e9;
            font-weight: 600;
            color: #2e7d32;
        }

        .dropdown-item.item-option.full-set-option:hover {
            background: #c8e6c9;
        }

        .dropdown-item.disabled {
            cursor: default;
            color: #adb5bd;
        }

        .dropdown-item.disabled:hover {
            background: transparent;
        }

        .expand-icon {
            transition: transform 0.3s ease;
            font-size: 12px;
        }

        .expand-icon.expanded {
            transform: rotate(90deg);
        }

        .pc-content {
            display: none;
        }

        .pc-content.show {
            display: block;
        }

        .room-content {
            display: none;
        }

        .room-content.show {
            display: block;
        }

        /* Animations for reason field */
        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                max-height: 500px;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 1;
                max-height: 500px;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                max-height: 0;
                transform: translateY(-10px);
            }
        }

        /* Enhanced Mobile Responsive Styles */
        @media (max-width: 480px) {
            .main-content {
                padding: 10px 5px;
            }

            .content-wrapper {
                padding: 0 10px;
            }

            .page-title {
                font-size: 18px;
            }

            .top-buttons {
                flex-direction: column;
                width: 100%;
            }

            .top-buttons button,
            .top-buttons a {
                width: 100%;
                font-size: 12px;
                padding: 8px 10px;
            }

            .table-container {
                width: 100%;
                transform: none;
                margin: 0;
            }

            table {
                font-size: 11px;
                min-width: 600px;
            }

            th, td {
                padding: 8px 5px;
            }

            .borrower-group {
                padding: 10px;
                margin-bottom: 15px;
            }

            .modal-content {
                padding: 15px;
                width: 95%;
                margin: 5% auto;
            }

            .form-group {
                margin-bottom: 15px;
            }

            input, select, textarea {
                font-size: 14px;
                padding: 10px;
            }

            .submit-btn {
                padding: 8px 12px;
                font-size: 13px;
            }

            .btn-return {
                padding: 6px 10px;
                font-size: 11px;
            }

            #map, #trackerMap {
                height: 250px;
            }

            .card-layout {
                padding: 0 10px;
            }

            .item-card {
                padding: 15px;
            }

            .card-details {
                gap: 8px;
            }

            .card-actions {
                margin-top: 10px;
            }

            .btn-return {
                width: 100%;
            }
        }
    </style>
@endpush

<div class="main-content">
    <div class="content-wrapper">
        <div class="page-header">
            <h1 class="page-title">Borrowed Items List</h1>
            <div class="top-buttons">
                <button onclick="openBorrowModal()"><i class="fas fa-plus"></i> Borrow Item</button>
                <button onclick="openTrackerModal()"><i class="fas fa-calendar-alt"></i> Monthly Tracker</button>
            </div>
        </div>

        <!-- Success/Error messages will be shown via SweetAlert -->
    </div>

    <!-- Desktop Table View - Grouped by Borrower -->
    <div class="table-container">
        <div class="table-responsive">
            @php
                // Group items by borrower name
                $borrowerGroups = [];
                
                foreach($items as $item) {
                    if ($item->latestBorrow && $item->latestBorrow->status === 'Borrowed') {
                        $borrowerName = $item->latestBorrow->borrower_name;
                        
                        if (!isset($borrowerGroups[$borrowerName])) {
                            $borrowerGroups[$borrowerName] = [
                                'borrower' => $item->latestBorrow,
                                'items' => []
                            ];
                        }
                        
                        // Prepare item data for JavaScript
                        $itemData = [
                            'id' => $item->id,
                            'device_category' => $item->device_category ?? '',
                            'serial_number' => $item->serial_number ?? '',
                            'room_title' => $item->room_title ?? '',
                            'description' => $item->description ?? 'N/A',
                            'latestBorrow' => [
                                'id' => $item->latestBorrow->id,
                                'status' => $item->latestBorrow->status,
                            ]
                        ];
                        
                        $borrowerGroups[$borrowerName]['items'][] = $itemData;
                    }
                }
                
                ksort($borrowerGroups);
            @endphp
            
            @forelse($borrowerGroups as $borrowerName => $group)
                <div class="borrower-group" style="margin-bottom: 30px; background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 2px solid #e9ecef;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            @if($group['borrower']->borrower_photo)
                                <img src="{{ asset('storage/' . $group['borrower']->borrower_photo) }}" 
                                     alt="Borrower" 
                                     style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;"
                                     onerror="this.onerror=null; this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                <div style="display: none; width: 50px; height: 50px; border-radius: 50%; background: #e9ecef; align-items: center; justify-content: center; font-size: 24px;">👤</div>
                            @else
                                <div style="width: 50px; height: 50px; border-radius: 50%; background: #e9ecef; display: flex; align-items: center; justify-content: center; font-size: 24px;">👤</div>
                            @endif
                            <div>
                                <h3 style="margin: 0; color: #2c3e50; font-size: 16px;">{{ $borrowerName }}</h3>
                                @if($group['borrower']->position)
                                    <p style="margin: 5px 0 0 0; color: #6c757d; font-size: 14px;">{{ $group['borrower']->position }}</p>
                                @endif
                                @if($group['borrower']->department)
                                    <p style="margin: 5px 0 0 0; color: #6c757d; font-size: 14px;">{{ $group['borrower']->department }}</p>
                                @endif
                            </div>
                        </div>
                        <button class="btn-return return-all-btn" 
                                data-borrower-name="{{ $borrowerName }}" 
                                data-items-id="items-{{ md5($borrowerName) }}"
                                style="padding: 12px 24px; font-size: 16px;">
                            <i class="fas fa-undo"></i> Return All Items
                        </button>
                        <script type="application/json" id="items-{{ md5($borrowerName) }}">
                            {!! json_encode($group['items']) !!}
                        </script>
                    </div>
                    
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;"><i class="fas fa-door-open"></i> Room</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;"><i class="fas fa-tags"></i> Category</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;"><i class="fas fa-barcode"></i> Serial #</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;"><i class="fas fa-info-circle"></i> Description</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;"><i class="fas fa-calendar"></i> Borrow Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Get actual item objects for display (not the prepared data)
                                $displayItems = collect($items)->filter(function($item) use ($borrowerName) {
                                    return $item->latestBorrow && 
                                           $item->latestBorrow->status === 'Borrowed' && 
                                           $item->latestBorrow->borrower_name === $borrowerName;
                                });
                            @endphp
                            @foreach($displayItems as $item)
                                <tr style="border-bottom: 1px solid #e9ecef;">
                                    <td style="padding: 12px;"><strong>{{ $item->room_title }}</strong></td>
                                    <td style="padding: 12px;">{{ $item->device_category }}</td>
                                    <td style="padding: 12px;"><code>{{ $item->serial_number }}</code></td>
                                    <td style="padding: 12px;">{{ $item->description }}</td>
                                    <td style="padding: 12px;">
                                        @if($item->latestBorrow && $item->latestBorrow->borrow_date)
                                            <div class="date-info">
                                                <span class="date-main">{{ \Carbon\Carbon::parse($item->latestBorrow->borrow_date)->format('M d, Y (g:i A)') }}</span>
                                                <span class="date-relative">{{ \Carbon\Carbon::parse($item->latestBorrow->borrow_date)->diffForHumans() }}</span>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <div style="text-align: center; padding: 40px; background: white; border-radius: 10px;">
                    <p style="color: #6c757d; font-size: 18px;">No borrowed items found.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Return Modal -->
    <div id="returnModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 10000; align-items: center; justify-content: center; overflow-y: auto; padding: 20px;" onclick="if(event.target.id === 'returnModal') closeReturnModal();">
        <div style="background: white; border-radius: 15px; max-width: 800px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
            <div style="padding: 25px; border-bottom: 2px solid #e9ecef; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: white; z-index: 10;">
                <h2 style="margin: 0; color: #2c3e50; font-size: 24px;"><i class="fas fa-undo"></i> Return Items</h2>
                <button onclick="closeReturnModal()" style="background: none; border: none; font-size: 28px; color: #6c757d; cursor: pointer; padding: 0; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.3s;">&times;</button>
            </div>
            
            <form id="returnForm" method="POST" action="/borrow/return-bulk" style="padding: 25px;">
                @csrf
                <div id="returnModalContent">
                    <!-- Items will be dynamically inserted here -->
                </div>
                
                <div id="unusableItemsSection" style="margin-top: 25px; display: none;">
                    <h3 style="color: #dc3545; margin-bottom: 15px; font-size: 18px;"><i class="fas fa-exclamation-triangle"></i> Unusable Items</h3>
                    <div id="unusableItemsList" style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                        <!-- Unusable items will be listed here -->
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; justify-content: flex-end; margin-top: 25px; padding-top: 20px; border-top: 2px solid #e9ecef;">
                    <button type="button" onclick="closeReturnModal()" style="padding: 12px 24px; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600;">Cancel</button>
                    <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #28a745, #20c997); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600;"><i class="fas fa-check"></i> Return Items</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Mobile Card Layout -->
    <div class="card-layout">
        @forelse($items as $item)
            <div class="item-card">
                <div class="card-header">
                    <div class="card-title">{{ $item->room_title }} - {{ $item->device_category }}</div>
                    <div class="card-status 
                        @if($item->status === 'Unusable') unusable
                        @elseif($item->borrow && $item->borrow->status === 'Borrowed') status-borrowed
                        @else status-usable @endif">
                        @if($item->status === 'Unusable')
                            ❌ Unusable
                        @elseif($item->borrow && $item->borrow->status === 'Borrowed')
                            Borrowed
                        @else
                            Usable
                        @endif
                    </div>
                </div>
                
                <div class="card-details">
                    <div class="card-detail">
                        <span class="detail-label">Serial #:</span>
                        <span class="detail-value"><code>{{ $item->serial_number }}</code></span>
                    </div>
                    <div class="card-detail">
                        <span class="detail-label">Description:</span>
                        <span class="detail-value">{{ $item->description }}</span>
                    </div>
                    @if($item->borrow && $item->borrow->borrower_name)
                    <div class="card-detail">
                        <span class="detail-label">Borrower:</span>
                        <span class="detail-value">{{ $item->borrow->borrower_name }}</span>
                    </div>
                    @endif
                    @if($item->borrow && $item->borrow->borrow_date)
                    <div class="card-detail">
                        <span class="detail-label">Borrow Date:</span>
                        <div class="detail-value">
                            <div class="date-info">
                                <span class="date-main">{{ \Carbon\Carbon::parse($item->borrow->borrow_date)->format('M d, Y (g:i A)') }}</span>
                                <span class="date-relative">{{ \Carbon\Carbon::parse($item->borrow->borrow_date)->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="card-actions">
                    @if($item->borrow && $item->borrow->status === 'Borrowed')
                        <form method="POST" action="/borrow/return/{{ $item->borrow->id }}">
                            @csrf
                            <button class="btn-return"><i class="fas fa-check"></i> Return</button>
                        </form>
                    @elseif($item->status === 'Unusable')
                        <button class="btn-return btn-disabled" disabled><i class="fas fa-times"></i> Not Usable</button>
                    @else
                        <button class="btn-return btn-disabled" disabled><i class="fas fa-check"></i> Returned</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <p>No items found.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal: Borrow Item -->
<div id="borrowModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeBorrowModal()">&times;</span>
        <h3>➕ Borrow Item</h3>

        <form method="POST" action="/borrow" enctype="multipart/form-data" id="borrowForm">
            @csrf
            <div class="form-group">
                <label for="room_item_id">Select Item</label>
                <div class="custom-dropdown">
                    <div class="dropdown-toggle" id="dropdownToggle" onclick="toggleDropdown()">
                        <span id="selectedText">-- Choose an item --</span>
                        <span>▼</span>
                    </div>
                    <div class="dropdown-menu" id="dropdownMenu">
                        {{-- Grouped by Room, then by PC# - Collapsible --}}
                        @foreach($groupedAvailableItems as $roomTitle => $pcGroups)
                            @php
                                $roomId = 'room-' . Str::slug($roomTitle);
                            @endphp
                            <div class="dropdown-item room-header" onclick="toggleRoom('{{ $roomId }}', event)">
                                <span>🏢 {{ $roomTitle }}</span>
                                <span class="expand-icon" id="icon-{{ $roomId }}">▶</span>
                            </div>
                            <div class="room-content" id="{{ $roomId }}">
                                @foreach($pcGroups as $pcNumber => $pcItems)
                                    @php
                                        $firstItem = $pcItems[0];
                                        $itemCount = count($pcItems);
                                        $pcId = 'pc-' . Str::slug($roomTitle) . '-' . $pcNumber;
                                        
                                        // Check if items share the same full_set_id (indicating a full set)
                                        $sharedFullSetId = null;
                                        $allHaveFullSetId = true;
                                        foreach($pcItems as $pcItem) {
                                            if ($pcItem->full_set_id) {
                                                if ($sharedFullSetId === null) {
                                                    $sharedFullSetId = $pcItem->full_set_id;
                                                } elseif ($sharedFullSetId !== $pcItem->full_set_id) {
                                                    $allHaveFullSetId = false;
                                                    break;
                                                }
                                            } else {
                                                $allHaveFullSetId = false;
                                            }
                                        }
                                        $isFullSet = ($allHaveFullSetId && $sharedFullSetId && count($pcItems) > 1);
                                        $fullSetId = $sharedFullSetId ?? '';
                                        
                                        // Get the most common full_set_id if items have different ones
                                        if (!$isFullSet && $itemCount > 1) {
                                            $fullSetIds = [];
                                            foreach($pcItems as $pcItem) {
                                                if ($pcItem->full_set_id) {
                                                    $fullSetIds[] = $pcItem->full_set_id;
                                                }
                                            }
                                            if (count($fullSetIds) > 0) {
                                                $fullSetId = $fullSetIds[0];
                                            }
                                        }
                                    @endphp
                                    
                                    {{-- PC# Header --}}
                                    <div class="dropdown-item pc-header" onclick="togglePC('{{ $pcId }}', event)">
                                        <span>└─ PC#{{ str_pad($pcNumber, 3, '0', STR_PAD_LEFT) }} ({{ $itemCount }} item{{ $itemCount > 1 ? 's' : '' }})</span>
                                        <span class="expand-icon" id="icon-{{ $pcId }}">▶</span>
                                    </div>
                                    
                                    {{-- PC# Content --}}
                                    <div class="pc-content" id="{{ $pcId }}">
                                        @if($isFullSet && $itemCount > 1)
                                            {{-- Full Set Option --}}
                                            <div class="dropdown-item item-option full-set-option" 
                                                onclick="selectItem('{{ $firstItem->id }}', '📦 Full Set ({{ $itemCount }} items) - Borrow All', '{{ $roomTitle }}', '{{ $pcNumber }}', '{{ $fullSetId }}', '1', event)">
                                                📦 Full Set ({{ $itemCount }} items) - Borrow All
                                            </div>
                                        @endif
                                        
                                        {{-- Individual items in PC# --}}
                                        @foreach($pcItems as $item)
                                            <div class="dropdown-item item-option" 
                                                onclick="selectItem('{{ $item->id }}', '{{ $item->device_category }} - {{ $item->serial_number }}', '{{ $roomTitle }}', '{{ $pcNumber }}', '{{ $item->full_set_id ?? $fullSetId }}', '{{ ($isFullSet && $itemCount > 1) ? '1' : ($item->is_full_item ? '1' : '0') }}', event)">
                                                {{ $item->device_category }} - {{ $item->serial_number }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                                
                                {{-- Individual items in this room (not part of PC#) --}}
                                @if(isset($individualAvailableItems[$roomTitle]) && count($individualAvailableItems[$roomTitle]) > 0)
                                    <div class="dropdown-item pc-header" style="background: #fff3cd;">
                                        <span>└─ Individual Items</span>
                                    </div>
                                    @foreach($individualAvailableItems[$roomTitle] as $item)
                                        <div class="dropdown-item item-option" 
                                            onclick="selectItem('{{ $item->id }}', '{{ $item->device_category }} - {{ $item->serial_number }}', '{{ $roomTitle }}', '', '{{ $item->full_set_id ?? '' }}', '{{ $item->is_full_item ? '1' : '0' }}', event)">
                                            {{ $item->device_category }} - {{ $item->serial_number }}
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                        
                        {{-- Rooms with only individual items (no PC# groups) --}}
                        @foreach($individualAvailableItems as $roomTitle => $items)
                            @if(!isset($groupedAvailableItems[$roomTitle]))
                                @php
                                    $roomId = 'room-' . Str::slug($roomTitle);
                                @endphp
                                <div class="dropdown-item room-header" onclick="toggleRoom('{{ $roomId }}', event)">
                                    <span>🏢 {{ $roomTitle }}</span>
                                    <span class="expand-icon" id="icon-{{ $roomId }}">▶</span>
                                </div>
                                <div class="room-content" id="{{ $roomId }}">
                                    @foreach($items as $item)
                                        <div class="dropdown-item item-option" 
                                            onclick="selectItem('{{ $item->id }}', '{{ $item->device_category }} - {{ $item->serial_number }}', '{{ $roomTitle }}', '', '{{ $item->full_set_id ?? '' }}', '{{ $item->is_full_item ? '1' : '0' }}', event)">
                                            {{ $item->device_category }} - {{ $item->serial_number }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <input type="hidden" name="room_item_id" id="room_item_id" required>
                </div>
            </div>

            <div class="form-group" id="fullSetGroup" style="display: none;">
                <label>
                    <input type="checkbox" name="borrow_full_set" id="borrow_full_set" value="1" checked>
                    <strong>Borrow Full Set</strong> - All items in PC#<span id="selectedPcNumber"></span> will be borrowed ({{ count($groupedAvailableItems) > 0 ? 'recommended' : '' }})
                </label>
                <small style="color: #6c757d; display: block; margin-top: 5px;">
                    <i class="fas fa-info-circle"></i> When checked, all items in this PC# will be borrowed together.
                </small>
            </div>

            <div class="form-group">
                <label for="borrower_name">Borrower Name</label>
                <input type="text" name="borrower_name" id="borrower_name" required>
            </div>

            <div class="form-group">
                <label for="borrower_photo">Photo of the Borrower</label>
                <input type="file" name="borrower_photo" id="borrower_photo" accept="image/jpeg,image/jpg,image/png,image/gif,image/jfif,image/webp,image/bmp,image/svg+xml" onchange="previewPhoto(this)">
                <small style="color: #6c757d; display: block; margin-top: 5px;">
                    <i class="fas fa-info-circle"></i> Accepted formats: JPG, JPEG, PNG, GIF, JFIF, WEBP, BMP, SVG (Max: 5MB)
                </small>
                <img id="photoPreview" class="photo-preview" alt="Photo preview">
            </div>

            <div class="form-group">
                <label for="position">Position</label>
                <input type="text" name="position" id="position" placeholder="e.g., Student, Faculty, Staff">
            </div>

            <div class="form-group">
                <label for="department">Department</label>
                <select name="department" id="department">
                    <option value="">-- Select Department --</option>
                    <option value="BSIT">BSIT</option>
                    <option value="BSHM">BSHM</option>
                    <option value="BSBA">BSBA</option>
                    <option value="BSED">BSED</option>
                    <option value="BEED">BEED</option>
                </select>
            </div>

            <div class="form-group">
                <button type="button" class="setup-map-btn" onclick="setupMap()">
                    <i class="fas fa-map-marker-alt"></i> Setup Map Location
                </button>
                <div id="map" style="display: none;"></div>
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
            </div>

            <div class="form-group">
                <label for="borrow_date">Borrow Date</label>
                <input type="datetime-local" name="borrow_date" id="borrow_date" required>
            </div>

            <div class="form-group">
                <label for="borrow_duration">Borrow Duration <span style="color: red;">*</span></label>
                <select name="borrow_duration" id="borrow_duration" required>
                    <option value="">-- Select Duration --</option>
                    <option value="1_day">1 Day</option>
                    <option value="2_days">2 Days</option>
                    <option value="3_days">3 Days</option>
                    <option value="4_days">4 Days</option>
                    <option value="1_week">1 Week</option>
                </select>
            </div>

            <div class="form-group">
                <label for="reason">Reason for Borrowing <span style="color: red;">*</span></label>
                <textarea name="reason" id="reason" rows="4" placeholder="Please provide a reason for borrowing this item..." required style="resize: vertical; min-height: 100px;"></textarea>
                <small style="color: #6c757d; display: block; margin-top: 5px;">
                    <i class="fas fa-info-circle"></i> Please describe why you need to borrow this item.
                </small>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-check"></i> Submit Borrow Request
            </button>
        </form>
    </div>
</div>

<!-- Modal: Monthly Tracker -->
<div id="trackerModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeTrackerModal()">&times;</span>
        <h3>📅 Monthly Activity Tracker</h3>
        
        <div id="trackerMap" style="margin-bottom: 20px;"></div>
        
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Borrower</th>
                        <th>Item</th>
                        <th>Category</th>
                        <th>Borrow Date</th>
                        <th>Returned Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                        <tr>
                            <td>
                                @if($activity->borrower_photo)
                                    <img src="{{ asset('storage/' . $activity->borrower_photo) }}" 
                                         alt="Borrower" 
                                         style="width: 30px; height: 30px; border-radius: 50%; margin-right: 5px; vertical-align: middle; object-fit: cover;"
                                         onerror="this.onerror=null; this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='inline-flex';">
                                    <span style="display: none; width: 30px; height: 30px; border-radius: 50%; background: #e9ecef; align-items: center; justify-content: center; font-size: 14px; margin-right: 5px;">👤</span>
                                @endif
                                {{ $activity->borrower_name }}
                                @if($activity->position)
                                    <br><small style="color: #6c757d;">{{ $activity->position }}</small>
                                @endif
                                @if($activity->department)
                                    <br><small style="color: #6c757d;">{{ $activity->department }}</small>
                                @endif
                            </td>
                            <td><code>{{ $activity->roomItem->serial_number ?? 'N/A' }}</code></td>
                            <td>{{ $activity->roomItem->device_category ?? 'N/A' }}</td>
                            <td>
                                <div class="date-info">
                                    <span class="date-main">{{ \Carbon\Carbon::parse($activity->borrow_date)->format('M d, Y (g:i A)') }}</span>
                                    <span class="date-relative">{{ \Carbon\Carbon::parse($activity->borrow_date)->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td>
                                @if($activity->return_date)
                                    <div class="date-info">
                                        <span class="date-main">{{ \Carbon\Carbon::parse($activity->return_date)->format('M d, Y (g:i A)') }}</span>
                                        <span class="date-relative">{{ \Carbon\Carbon::parse($activity->return_date)->diffForHumans() }}</span>
                                    </div>
                                @else
                                    <span class="status-badge status-borrowed">Not Returned</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge @if($activity->status === 'Borrowed') status-borrowed @else status-usable @endif">
                                    {{ $activity->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon">📊</div>
                                    <p>No activity found this month.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Leaflet JS 2025 -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder@1.13.0/dist/Control.Geocoder.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Initialize SweetAlert for session messages
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#28a745',
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#dc3545'
        });
    @endif

    @if (session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Warning!',
            text: '{{ session('warning') }}',
            confirmButtonColor: '#ffc107'
        });
    @endif

    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validation Error!',
            html: '<ul style="text-align: left; margin: 10px 0;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
            confirmButtonColor: '#dc3545'
        });
    @endif

    let map = null;
    let trackerMap = null;
    let marker = null;
    let trackerMarkers = [];

    // Return Modal Functions - Make sure it's globally accessible
    window.openReturnModal = function(borrowerName, items) {
        console.log('openReturnModal called with:', { borrowerName, itemsCount: items ? items.length : 0, items });
        try {
            // Validate inputs
            if (!borrowerName) {
                console.error('Borrower name is missing');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Borrower name is missing. Please try again.',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }
            
            if (!items || !Array.isArray(items) || items.length === 0) {
                console.error('Items array is missing or empty:', items);
                Swal.fire({
                    icon: 'error',
                    title: 'No Items',
                    text: 'No items found for this borrower.',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }
            
            const modal = document.getElementById('returnModal');
            const content = document.getElementById('returnModalContent');
            const unusableSection = document.getElementById('unusableItemsSection');
            const unusableList = document.getElementById('unusableItemsList');
            
            if (!modal || !content) {
                console.error('Modal elements not found');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Modal elements not found. Please refresh the page.',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }
            
            // Clear previous content
            content.innerHTML = '';
            if (unusableList) unusableList.innerHTML = '';
            if (unusableSection) unusableSection.style.display = 'none';
            
            // Add borrower name header
            const header = document.createElement('div');
            header.style.cssText = 'margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #e9ecef;';
            header.innerHTML = `<h3 style="margin: 0; color: #2c3e50;">Returning items for: <strong>${borrowerName}</strong></h3>`;
            content.appendChild(header);
            
            // Create items list
            const itemsContainer = document.createElement('div');
            itemsContainer.style.cssText = 'display: flex; flex-direction: column; gap: 15px;';
            
            let validItemsCount = 0;
            
            items.forEach((item, index) => {
            // Get borrowId - handle both possible data structures
            let borrowId = null;
            if (item.latestBorrow && item.latestBorrow.id) {
                borrowId = item.latestBorrow.id;
            } else if (item.latest_borrow && item.latest_borrow.id) {
                borrowId = item.latest_borrow.id;
            }
            
            // Skip items without a valid borrowId
            if (!borrowId) {
                console.error('Item missing borrowId:', item);
                return; // Skip this item
            }
            
            validItemsCount++;
            
            const itemDiv = document.createElement('div');
            itemDiv.style.cssText = 'background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px;';
            // Store item name for validation
            const itemName = `${item.device_category || 'N/A'} (${item.serial_number || 'No Serial'})`;
            itemDiv.setAttribute('data-item-name', itemName);
            
            const itemInfo = document.createElement('div');
            itemInfo.style.cssText = 'margin-bottom: 12px;';
            itemInfo.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                    <div>
                        <strong style="color: #2c3e50; font-size: 16px;" class="item-name-display">${item.device_category || 'N/A'}</strong>
                        <p style="margin: 5px 0; color: #6c757d; font-size: 14px;">${item.room_title || ''} - ${item.serial_number || ''}</p>
                        <p style="margin: 0; color: #6c757d; font-size: 13px;">${item.description || 'No description'}</p>
                    </div>
                </div>
            `;
            
            const statusDiv = document.createElement('div');
            statusDiv.style.cssText = 'display: flex; gap: 10px; align-items: center;';
            
            const usableBtn = document.createElement('button');
            usableBtn.type = 'button';
            usableBtn.className = 'status-btn-usable';
            usableBtn.style.cssText = 'flex: 1; padding: 10px; background: #28a745; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s;';
            usableBtn.innerHTML = '<i class="fas fa-check-circle"></i> Usable';
            
            usableBtn.onclick = function() {
                setItemStatus(borrowId, 'Usable', usableBtn, unusableBtn, item, index);
            };
            
            const unusableBtn = document.createElement('button');
            unusableBtn.type = 'button';
            unusableBtn.className = 'status-btn-unusable';
            unusableBtn.style.cssText = 'flex: 1; padding: 10px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s;';
            unusableBtn.innerHTML = '<i class="fas fa-times-circle"></i> Unusable';
            unusableBtn.onclick = function() {
                setItemStatus(borrowId, 'Unusable', usableBtn, unusableBtn, item, index);
            };
            
            statusDiv.appendChild(usableBtn);
            statusDiv.appendChild(unusableBtn);
            
            // Hidden input for form submission
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = `items[${borrowId}][status]`;
            statusInput.value = 'Usable'; // Default
            statusInput.id = `status_${borrowId}`;
            
            const roomItemInput = document.createElement('input');
            roomItemInput.type = 'hidden';
            roomItemInput.name = `items[${borrowId}][room_item_id]`;
            roomItemInput.value = item.id;
            
            // Create a wrapper for the visible content (to help with reason field insertion)
            const contentWrapper = document.createElement('div');
            contentWrapper.className = 'item-content-wrapper';
            
            contentWrapper.appendChild(itemInfo);
            contentWrapper.appendChild(statusDiv);
            
            itemDiv.appendChild(contentWrapper);
            itemDiv.appendChild(statusInput);
            itemDiv.appendChild(roomItemInput);
            
            itemsContainer.appendChild(itemDiv);
            });
            
            if (validItemsCount === 0) {
                const errorMsg = document.createElement('div');
                errorMsg.style.cssText = 'padding: 20px; text-align: center; color: #dc3545;';
                errorMsg.innerHTML = '<p><i class="fas fa-exclamation-triangle"></i> No valid items found to return.</p>';
                content.appendChild(errorMsg);
            } else {
                content.appendChild(itemsContainer);
            }
            
            // Show modal
            console.log('Showing modal...');
            modal.style.display = 'flex';
            modal.style.visibility = 'visible';
            modal.style.opacity = '1';
            document.body.style.overflow = 'hidden';
            
            // Scroll to top of modal
            modal.scrollTop = 0;
            
            console.log('Modal should be visible now. Modal display:', modal.style.display);
            
        } catch (error) {
            console.error('Error opening return modal:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while opening the return modal. Please try again.',
                confirmButtonColor: '#dc3545'
            });
        }
    };
    
    // Also keep the function name without window for backward compatibility
    function openReturnModal(borrowerName, items) {
        return window.openReturnModal(borrowerName, items);
    }
    
    function setItemStatus(borrowId, status, usableBtn, unusableBtn, item, index) {
        const statusInput = document.getElementById(`status_${borrowId}`);
        const reasonInputId = `reason_${borrowId}`;
        const reasonInput = document.getElementById(reasonInputId);
        const unusableSection = document.getElementById('unusableItemsSection');
        const unusableList = document.getElementById('unusableItemsList');
        
        // Update status input
        statusInput.value = status;
        
        // Update button styles
        if (status === 'Usable') {
            usableBtn.style.background = '#28a745';
            usableBtn.style.transform = 'scale(1.05)';
            unusableBtn.style.background = '#6c757d';
            unusableBtn.style.transform = 'scale(1)';
            
            // Remove reason input container if exists
            const reasonContainer = document.getElementById(`reason_container_${borrowId}`);
            if (reasonContainer) {
                reasonContainer.style.animation = 'slideUp 0.3s ease-out';
                setTimeout(() => {
                    reasonContainer.remove();
                }, 300);
            }
        } else {
            usableBtn.style.background = '#6c757d';
            usableBtn.style.transform = 'scale(1)';
            unusableBtn.style.background = '#dc3545';
            unusableBtn.style.transform = 'scale(1.05)';
            
            // Add reason input if it doesn't exist
            if (!reasonInput) {
                console.log('Creating reason field for borrowId:', borrowId);
                const reasonContainer = document.createElement('div');
                reasonContainer.id = `reason_container_${borrowId}`;
                reasonContainer.className = 'reason-field-container';
                // Make absolutely sure it's visible
                reasonContainer.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important; margin-top: 15px; padding: 15px; background: #fff3cd; border: 2px solid #ffc107; border-radius: 8px; animation: slideDown 0.3s ease-out; width: 100%; box-sizing: border-box;';
                
                const reasonHeader = document.createElement('div');
                reasonHeader.style.cssText = 'display: flex; align-items: center; gap: 8px; margin-bottom: 10px; color: #856404; font-weight: 600; font-size: 14px;';
                reasonHeader.innerHTML = `<i class="fas fa-exclamation-triangle"></i> <span>Reason for marking as Unusable (Required):</span>`;
                
                const reasonTextarea = document.createElement('textarea');
                reasonTextarea.id = reasonInputId;
                reasonTextarea.name = `items[${borrowId}][reason]`;
                reasonTextarea.required = true;
                reasonTextarea.placeholder = 'Please describe the damage or issue with this item...';
                reasonTextarea.style.cssText = 'width: 100%; padding: 12px; border: 2px solid #ffc107; border-radius: 6px; font-size: 14px; min-height: 100px; resize: vertical; background: white; font-family: inherit; box-sizing: border-box;';
                
                const reasonNote = document.createElement('div');
                reasonNote.style.cssText = 'margin-top: 8px; font-size: 12px; color: #856404; font-style: italic;';
                reasonNote.innerHTML = '<i class="fas fa-info-circle"></i> This note will be saved to the maintenance page for monitoring.';
                
                reasonContainer.appendChild(reasonHeader);
                reasonContainer.appendChild(reasonTextarea);
                reasonContainer.appendChild(reasonNote);
                
                // Find the statusDiv (parent of the buttons)
                const statusDiv = unusableBtn.parentElement;
                if (statusDiv) {
                    // Find the contentWrapper (parent of statusDiv)
                    const contentWrapper = statusDiv.parentElement;
                    if (contentWrapper && contentWrapper.classList.contains('item-content-wrapper')) {
                        // Insert right after statusDiv
                        contentWrapper.insertBefore(reasonContainer, statusDiv.nextSibling);
                        console.log('Reason field inserted after statusDiv in contentWrapper');
                    } else {
                        // Fallback: append after statusDiv
                        if (statusDiv.nextSibling) {
                            statusDiv.parentNode.insertBefore(reasonContainer, statusDiv.nextSibling);
                        } else {
                            statusDiv.parentNode.appendChild(reasonContainer);
                        }
                        console.log('Reason field inserted after statusDiv (fallback)');
                    }
                } else {
                    // Last resort: find item container and append
                    const itemContainer = unusableBtn.closest('div[style*="background: #f8f9fa"]');
                    if (itemContainer) {
                        itemContainer.appendChild(reasonContainer);
                        console.log('Reason field appended to itemContainer (last resort)');
                    } else {
                        console.error('Could not find container for reason field');
                    }
                }
                
                // Force visibility and scroll
                setTimeout(() => {
                    const container = document.getElementById(`reason_container_${borrowId}`);
                    if (container) {
                        container.style.display = 'block';
                        container.style.visibility = 'visible';
                        container.style.opacity = '1';
                        container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        console.log('Reason field should be visible now');
                    }
                }, 50);
            } else {
                // If reason input exists, make sure it's visible
                const reasonContainer = document.getElementById(`reason_container_${borrowId}`);
                if (reasonContainer) {
                    reasonContainer.style.display = 'block';
                    reasonContainer.style.visibility = 'visible';
                    reasonContainer.style.opacity = '1';
                    reasonContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }
        }
        
        // Update unusable items list
        updateUnusableItemsList();
    }
    
    function updateUnusableItemsList() {
        const unusableSection = document.getElementById('unusableItemsSection');
        const unusableList = document.getElementById('unusableItemsList');
        const form = document.getElementById('returnForm');
        
        // Find all items marked as Unusable
        const unusableItems = [];
        const statusInputs = form.querySelectorAll('input[name*="[status]"]');
        
        statusInputs.forEach(input => {
            if (input.value === 'Unusable') {
                const borrowId = input.name.match(/\[(\d+)\]/)[1];
                const itemDiv = input.closest('div[style*="background: #f8f9fa"]');
                if (itemDiv) {
                    const itemName = itemDiv.querySelector('strong')?.textContent || 'Unknown Item';
                    const itemDetails = itemDiv.querySelector('p')?.textContent || '';
                    unusableItems.push({
                        id: borrowId,
                        name: itemName,
                        details: itemDetails
                    });
                }
            }
        });
        
        if (unusableItems.length > 0) {
            unusableList.innerHTML = '';
            unusableItems.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.style.cssText = 'padding: 10px; margin-bottom: 8px; background: white; border-radius: 6px; border-left: 4px solid #dc3545;';
                itemDiv.innerHTML = `
                    <strong style="color: #dc3545;">${item.name}</strong>
                    <p style="margin: 5px 0 0 0; color: #6c757d; font-size: 13px;">${item.details}</p>
                `;
                unusableList.appendChild(itemDiv);
            });
            unusableSection.style.display = 'block';
        } else {
            unusableSection.style.display = 'none';
        }
    }
    
    function closeReturnModal() {
        const modal = document.getElementById('returnModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        // Reset form
        const form = document.getElementById('returnForm');
        if (form) {
            form.reset();
        }
    }
    
    // Add event listeners for Return All Items buttons
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded - Setting up Return All Items buttons');
        
        // Handle Return All Items button clicks
        const returnButtons = document.querySelectorAll('.return-all-btn');
        console.log('Found', returnButtons.length, 'Return All Items buttons');
        
        returnButtons.forEach((button, index) => {
            console.log('Setting up button', index, button);
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Return All Items button clicked!', this);
                
                const borrowerName = this.getAttribute('data-borrower-name');
                const itemsId = this.getAttribute('data-items-id');
                
                console.log('Borrower name:', borrowerName);
                console.log('Items ID:', itemsId);
                
                if (!borrowerName) {
                    console.error('Borrower name is missing');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Borrower name is missing. Please refresh the page.',
                        confirmButtonColor: '#dc3545'
                    });
                    return;
                }
                
                if (!itemsId) {
                    console.error('Items ID is missing');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Items data ID is missing. Please refresh the page.',
                        confirmButtonColor: '#dc3545'
                    });
                    return;
                }
                
                // Get items from script tag
                const itemsScript = document.getElementById(itemsId);
                if (!itemsScript) {
                    console.error('Items script tag not found:', itemsId);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Items data not found. Please refresh the page.',
                        confirmButtonColor: '#dc3545'
                    });
                    return;
                }
                
                try {
                    const items = JSON.parse(itemsScript.textContent);
                    console.log('Parsed items:', items);
                    console.log('Opening modal for:', borrowerName, 'with', items.length, 'items');
                    openReturnModal(borrowerName, items);
                } catch (error) {
                    console.error('Error parsing items JSON:', error);
                    console.error('Items content:', itemsScript.textContent);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load items. Please refresh the page and try again.',
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        });
        
        // Form submission handler
        const returnForm = document.getElementById('returnForm');
        if (returnForm) {
            returnForm.addEventListener('submit', function(e) {
                // Validate that there are items to return
                const statusInputs = returnForm.querySelectorAll('input[name*="[status]"]');
                if (statusInputs.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'No Items to Return',
                        text: 'There are no items selected for return. Please try again.',
                        confirmButtonColor: '#dc3545'
                    });
                    return false;
                }
                
                // Validate that all items have valid borrow IDs
                let hasInvalidItems = false;
                statusInputs.forEach(input => {
                    const name = input.name;
                    const match = name.match(/items\[(\d+)\]/);
                    if (!match || !match[1]) {
                        hasInvalidItems = true;
                    }
                });
                
                if (hasInvalidItems) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Form Data',
                        text: 'Some items have invalid data. Please refresh the page and try again.',
                        confirmButtonColor: '#dc3545'
                    });
                    return false;
                }
                
                // Validate that all unusable items have reasons
                const unusableStatusInputs = Array.from(statusInputs).filter(input => input.value === 'Unusable');
                const missingReasons = [];
                
                for (let input of unusableStatusInputs) {
                    const match = input.name.match(/items\[(\d+)\]/);
                    if (match && match[1]) {
                        const borrowId = match[1];
                        const reasonInput = returnForm.querySelector(`textarea[name="items[${borrowId}][reason]"]`);
                        if (!reasonInput || !reasonInput.value || !reasonInput.value.trim()) {
                            // Find the item name from data attribute or display
                            const itemContainer = input.closest('div[style*="background: #f8f9fa"]');
                            let itemName = 'Unknown Item';
                            if (itemContainer) {
                                // Try data attribute first
                                itemName = itemContainer.getAttribute('data-item-name') || 
                                          itemContainer.querySelector('strong.item-name-display')?.textContent ||
                                          itemContainer.querySelector('strong')?.textContent ||
                                          'Unknown Item';
                            }
                            missingReasons.push(itemName);
                        }
                    }
                }
                
                if (missingReasons.length > 0) {
                    e.preventDefault();
                    let message = 'Please provide a reason for the following items marked as Unusable:\n\n';
                    missingReasons.forEach((name, index) => {
                        message += `${index + 1}. ${name}\n`;
                    });
                    message += '\nThese notes will be saved to the maintenance page for monitoring.';
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Reasons',
                        text: message,
                        confirmButtonColor: '#dc3545',
                        width: '500px'
                    });
                    return false;
                }
            });
        }
    });
    
    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('returnModal');
        if (event.target === modal) {
            closeReturnModal();
        }
    });

    // Original JavaScript functionality preserved
    function openBorrowModal() {
        document.getElementById("borrowModal").style.display = "block";
        document.body.style.overflow = "hidden"; // Prevent background scrolling
    }

    function closeBorrowModal() {
        document.getElementById("borrowModal").style.display = "none";
        document.body.style.overflow = "auto";
        // Reset form
        if (map) {
            map.remove();
            map = null;
            marker = null;
        }
        document.getElementById("map").style.display = "none";
        document.getElementById("photoPreview").classList.remove("show");
        document.getElementById("photoPreview").src = "";
    }

    function openTrackerModal() {
        document.getElementById("trackerModal").style.display = "block";
        document.body.style.overflow = "hidden";
        // Initialize tracker map
        setTimeout(initTrackerMap, 100);
    }

    function closeTrackerModal() {
        document.getElementById("trackerModal").style.display = "none";
        document.body.style.overflow = "auto";
        if (trackerMap) {
            trackerMap.remove();
            trackerMap = null;
            trackerMarkers = [];
        }
    }

    // Close modal when clicking outside (enhanced)
    window.onclick = function(event) {
        const borrowModal = document.getElementById("borrowModal");
        const trackerModal = document.getElementById("trackerModal");
        
        if (event.target === borrowModal) {
            closeBorrowModal();
        }
        if (event.target === trackerModal) {
            closeTrackerModal();
        }
    };

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeBorrowModal();
            closeTrackerModal();
        }
    });

    // Auto-set current date/time for borrow date input
    document.addEventListener('DOMContentLoaded', function() {
        const borrowDateInput = document.querySelector('input[name="borrow_date"]');
        if (borrowDateInput) {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            borrowDateInput.value = now.toISOString().slice(0, 16);
        }
    });

    // Custom Dropdown Functions
    let selectedItemData = {
        id: '',
        pcNumber: '',
        roomTitle: '',
        fullSetId: '',
        isFullItem: '0'
    };

    function toggleDropdown() {
        const menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('show');
    }

    function toggleRoom(roomId, event) {
        event.stopPropagation();
        const content = document.getElementById(roomId);
        const icon = document.getElementById('icon-' + roomId);
        content.classList.toggle('show');
        icon.classList.toggle('expanded');
    }

    function togglePC(pcId, event) {
        event.stopPropagation();
        const content = document.getElementById(pcId);
        const icon = document.getElementById('icon-' + pcId);
        content.classList.toggle('show');
        icon.classList.toggle('expanded');
    }

    function selectItem(itemId, itemText, roomTitle, pcNumber, fullSetId, isFullItem, event) {
        event.stopPropagation();
        
        selectedItemData = {
            id: itemId,
            pcNumber: pcNumber,
            roomTitle: roomTitle,
            fullSetId: fullSetId,
            isFullItem: isFullItem
        };
        
        document.getElementById('room_item_id').value = itemId;
        document.getElementById('selectedText').textContent = itemText;
        document.getElementById('dropdownMenu').classList.remove('show');
        
        // Check if full set option should be shown
        checkFullSet();
    }

    // Close dropdown when clicking outside
    window.addEventListener('click', function(event) {
        const dropdown = document.querySelector('.custom-dropdown');
        if (!dropdown.contains(event.target)) {
            document.getElementById('dropdownMenu').classList.remove('show');
        }
    });

    // Check if selected item is part of a full set
    function checkFullSet() {
        const pcNumber = selectedItemData.pcNumber;
        const fullSetId = selectedItemData.fullSetId;
        const isFullItem = selectedItemData.isFullItem === '1';
        const fullSetGroup = document.getElementById('fullSetGroup');
        const selectedPcNumberSpan = document.getElementById('selectedPcNumber');
        
        // Check if this item belongs to a PC# group (has pcNumber)
        if (pcNumber && pcNumber !== '') {
            fullSetGroup.style.display = 'block';
            selectedPcNumberSpan.textContent = pcNumber;
            
            // Auto-check the full set checkbox if it's a full set option
            if (document.getElementById('room_item_id').value && isFullItem && fullSetId) {
                // Check if this is the full set option by checking the selected text
                const selectedText = document.getElementById('selectedText').textContent;
                if (selectedText.includes('Full Set')) {
                    document.getElementById('borrow_full_set').checked = true;
                } else {
                    document.getElementById('borrow_full_set').checked = false;
                }
            }
        } else {
            fullSetGroup.style.display = 'none';
            document.getElementById('borrow_full_set').checked = false;
        }
    }

    // Preview photo
    function previewPhoto(input) {
        const preview = document.getElementById('photoPreview');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileSize = file.size / 1024 / 1024; // Size in MB
            
            // Check file size (max 5MB)
            if (fileSize > 5) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'The photo must be less than 5MB. Please choose a smaller file.',
                    confirmButtonColor: '#dc3545'
                });
                input.value = '';
                preview.classList.remove('show');
                preview.src = '';
                return;
            }
            
            // Check if it's an image file
            if (!file.type.match('image.*')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Please select a valid image file (JPG, JPEG, PNG, GIF, JFIF, WEBP, BMP, or SVG).',
                    confirmButtonColor: '#dc3545'
                });
                input.value = '';
                preview.classList.remove('show');
                preview.src = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.add('show');
                preview.onerror = function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Image Load Error',
                        text: 'There was an error loading the image. Please try another file.',
                        confirmButtonColor: '#dc3545'
                    });
                    preview.classList.remove('show');
                };
            };
            reader.onerror = function() {
                Swal.fire({
                    icon: 'error',
                    title: 'File Read Error',
                    text: 'There was an error reading the file. Please try again.',
                    confirmButtonColor: '#dc3545'
                });
                input.value = '';
                preview.classList.remove('show');
            };
            reader.readAsDataURL(file);
        } else {
            preview.classList.remove('show');
            preview.src = '';
        }
    }

    // Setup map with satellite view
    function setupMap() {
        const mapDiv = document.getElementById('map');
        if (map) {
            mapDiv.style.display = mapDiv.style.display === 'none' ? 'block' : 'none';
            return;
        }

        mapDiv.style.display = 'block';
        
        // Initialize map with satellite view (using Esri World Imagery)
        map = L.map('map', {
            center: [14.5995, 120.9842], // Default to Philippines
            zoom: 15
        });

        // Add satellite tile layer (Esri World Imagery)
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '© Esri',
            maxZoom: 19
        }).addTo(map);

        // Add marker
        marker = L.marker([14.5995, 120.9842], {draggable: true}).addTo(map);
        
        // Update coordinates when marker is dragged
        marker.on('dragend', function(e) {
            const pos = marker.getLatLng();
            document.getElementById('latitude').value = pos.lat;
            document.getElementById('longitude').value = pos.lng;
        });

        // Get user's current location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 18);
                marker.setLatLng([lat, lng]);
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            });
        }

        // Update coordinates on click
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            marker.setLatLng([lat, lng]);
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });

        // Initialize with default values if not set
        if (!document.getElementById('latitude').value) {
            document.getElementById('latitude').value = '14.5995';
            document.getElementById('longitude').value = '120.9842';
        }
    }

    // Initialize tracker map with all borrower and item locations
    function initTrackerMap() {
        if (trackerMap) return;

        const activities = @json($activities);
        
        trackerMap = L.map('trackerMap', {
            center: [14.5995, 120.9842],
            zoom: 13
        });

        // Add satellite tile layer
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '© Esri',
            maxZoom: 19
        }).addTo(trackerMap);

        const bounds = [];
        
        activities.forEach(function(activity) {
            if (activity.latitude && activity.longitude) {
                const lat = parseFloat(activity.latitude);
                const lng = parseFloat(activity.longitude);
                
                // Create popup content
                const roomItem = activity.room_item || {};
                const popupContent = `
                    <div style="min-width: 200px;">
                        <strong>${activity.borrower_name}</strong><br>
                        ${activity.position ? activity.position + '<br>' : ''}
                        ${activity.department ? activity.department + '<br>' : ''}
                        <hr>
                        <strong>Item:</strong> ${roomItem.serial_number || 'N/A'}<br>
                        <strong>Category:</strong> ${roomItem.device_category || 'N/A'}<br>
                        <strong>Status:</strong> ${activity.status}<br>
                        <strong>Borrow Date:</strong> ${new Date(activity.borrow_date).toLocaleString()}
                    </div>
                `;
                
                // Create marker with different colors for borrower and item
                const markerColor = activity.status === 'Borrowed' ? 'red' : 'green';
                const icon = L.divIcon({
                    className: 'custom-marker',
                    html: `<div style="background-color: ${markerColor}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>`,
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                });
                
                const m = L.marker([lat, lng], {icon: icon})
                    .addTo(trackerMap);
                
                // Add click event to show borrower modal (no popup, only modal)
                m.on('click', function() {
                    showBorrowerModal(activity);
                });
                
                trackerMarkers.push(m);
                bounds.push([lat, lng]);
            }
        });

        // Fit map to show all markers
        if (bounds.length > 0) {
            trackerMap.fitBounds(bounds, {padding: [50, 50]});
        }
    }

    // Show borrower modal when marker is clicked
    function showBorrowerModal(activity) {
        const roomItem = activity.room_item || {};
        const borrowerPhoto = activity.borrower_photo ? `/storage/${activity.borrower_photo}` : null;
        
        let htmlContent = `
            <div style="text-align: left; padding: 10px;">
                <div style="display: flex; align-items: center; margin-bottom: 20px; gap: 15px;">
                    ${borrowerPhoto ? `<img src="${borrowerPhoto}" alt="Borrower" style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid #667eea; object-fit: cover;" onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';"><div style="display: none; width: 80px; height: 80px; border-radius: 50%; background: #e9ecef; align-items: center; justify-content: center; font-size: 32px;">👤</div>` : '<div style="width: 80px; height: 80px; border-radius: 50%; background: #e9ecef; display: flex; align-items: center; justify-content: center; font-size: 32px;">👤</div>'}
                    <div>
                        <h3 style="margin: 0; color: #2c3e50;">${activity.borrower_name}</h3>
                        ${activity.position ? `<p style="margin: 5px 0; color: #6c757d;"><strong>Position:</strong> ${activity.position}</p>` : ''}
                        ${activity.department ? `<p style="margin: 5px 0; color: #6c757d;"><strong>Department:</strong> ${activity.department}</p>` : ''}
                    </div>
                </div>
                <hr style="margin: 20px 0; border: none; border-top: 2px solid #e9ecef;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <h4 style="margin: 0 0 10px 0; color: #667eea;">📦 Item Information</h4>
                        <p style="margin: 5px 0;"><strong>Serial #:</strong> ${roomItem.serial_number || 'N/A'}</p>
                        <p style="margin: 5px 0;"><strong>Category:</strong> ${roomItem.device_category || 'N/A'}</p>
                        <p style="margin: 5px 0;"><strong>Room:</strong> ${roomItem.room_title || 'N/A'}</p>
                    </div>
                    <div>
                        <h4 style="margin: 0 0 10px 0; color: #667eea;">📅 Borrow Details</h4>
                        <p style="margin: 5px 0;"><strong>Status:</strong> <span style="padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600; ${activity.status === 'Borrowed' ? 'background: #fff3cd; color: #856404;' : 'background: #d4edda; color: #155724;'}">${activity.status}</span></p>
                        <p style="margin: 5px 0;"><strong>Borrow Date:</strong><br>${new Date(activity.borrow_date).toLocaleString()}</p>
                        ${activity.return_date ? `<p style="margin: 5px 0;"><strong>Return Date:</strong><br>${new Date(activity.return_date).toLocaleString()}</p>` : '<p style="margin: 5px 0; color: #dc3545;"><strong>Return Date:</strong> Not Returned</p>'}
                    </div>
                </div>
                ${activity.latitude && activity.longitude ? `
                    <hr style="margin: 20px 0; border: none; border-top: 2px solid #e9ecef;">
                    <div>
                        <h4 style="margin: 0 0 10px 0; color: #667eea;">📍 Location</h4>
                        <p style="margin: 5px 0;"><strong>Coordinates:</strong> ${parseFloat(activity.latitude).toFixed(6)}, ${parseFloat(activity.longitude).toFixed(6)}</p>
                        <a href="https://www.google.com/maps?q=${activity.latitude},${activity.longitude}" target="_blank" style="color: #667eea; text-decoration: none;">🗺️ View on Google Maps</a>
                    </div>
                ` : ''}
            </div>
        `;

        Swal.fire({
            title: 'Borrower Information',
            html: htmlContent,
            width: '700px',
            confirmButtonText: 'Close',
            confirmButtonColor: '#667eea',
            customClass: {
                popup: 'borrower-modal-popup'
            }
        });
    }
</script>

<style>
    .borrower-modal-popup {
        border-radius: 15px;
    }
</style>
@endsection