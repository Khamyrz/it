<?php use Milon\Barcode\Facades\DNS1DFacade as DNS1D; ?>

@extends('layouts.app')
@section('title', 'Room Management')
@section('content')

@push('styles')
<style>
/* Room Group Styles - Updated to match maintenance.blade.php */
.room-group {
    margin-bottom: 25px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    transition: all 0.3s ease;
}

.room-group:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
}
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

        .header {
            background: #fff;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        /* Updated styling for success and error messages with blur effects */
        .alert-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 25px 35px;
            border-radius: 12px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 450px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 9999;
            animation: alertSlideIn 0.3s ease-out;
        }

        .alert-message .alert-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .alert-message .alert-text {
            margin: 0;
            font-weight: 600;
        }

        .alert-message .alert-okay-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s ease;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .alert-message .alert-okay-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }

        @keyframes alertSlideIn {
            0% { 
                opacity: 0; 
                transform: translate(-50%, -50%) scale(0.8) translateY(-20px); 
            }
            100% { 
                opacity: 1; 
                transform: translate(-50%, -50%) scale(1) translateY(0); 
            }
        }

        .alert-message.success {
            background: rgba(40, 167, 69, 0.9);
            color: #fff;
        }

        .alert-message.error {
            background: rgba(220, 53, 69, 0.9);
            color: #fff;
        }

        .alert-message.warning {
            background: rgba(255, 193, 7, 0.9);
            color: #000;
        }

        .alert-message.info {
            background: rgba(23, 162, 184, 0.9);
            color: #fff;
        }

        .alert-message i {
            margin-bottom: 5px;
            font-size: 20px;
        }

        .alert-message ul {
            list-style: none;
            padding: 0;
            margin-top: 5px;
        }

        .alert-message button {
            background: none;
            border: 1px solid; /* Use the alert's border color */
            color: inherit; /* Inherit text color from the alert */
            padding: 5px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .alert-message.success button:hover {
            background: #c3e6cb;
        }

        .alert-message.error button:hover {
            background: #f5c6cb;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .top-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            gap: 10px;
        }

        .back-button, .add-item-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .back-button {
            background: #007bff;
            color: white;
        }

        .add-item-btn {
            background: #28a745;
            color: white;
        }

        /* Updated CSS for centering the table container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center; /* Centers all child elements */
        }

        .table-container {
            background: #fff;
            border-radius: 5px;
            overflow: auto;
            max-height: 70vh;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            width: 100%; /* Ensures it takes full width of container */
            max-width: 1200px; /* Prevents it from getting too wide */
            margin: 0 auto; /* Additional centering */
        }

        /* If you want to keep the top-buttons left-aligned while centering only the table */
        .top-buttons {
            display: flex;
            justify-content: flex-end; /* Changed from space-between to flex-end */
            margin-bottom: 15px;
            gap: 10px;
            width: 100%;
            max-width: 1200px;
            /* Match the table container width */
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .table-container {
                width: 100%;
                margin: 0;
            }
            
            .top-buttons {
                flex-direction: column;
                width: 100%;
            }
        }
        th {
            background: #f8f9fa;
            padding: 10px 8px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
            font-size: 12px;
            font-weight: 600;
            position: sticky;
            top: 0;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #e9ecef;
            font-size: 12px;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .thumb {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }
        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .page-header {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .page-title {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 10px;
        }

        .button-row {
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }

        .add-item-btn {
            padding: 8px 15px;
            background: #6f42c1;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .add-item-btn i {
            font-size: 16px;
        }
        /* Hide header add item button; we'll use a floating action button */
        .page-header .button-row { display: none; }

        /* Floating Add (+) Button */
        .fab-add-item {
            position: fixed;
            right: 24px;
            bottom: 24px;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            border: none;
            outline: none;
            cursor: pointer;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: #ffffff;
            box-shadow: 0 10px 24px rgba(0,0,0,0.18);
            font-size: 42px;
            line-height: 0;
            display: grid;
            place-items: center;
            z-index: 1000;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .fab-add-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(0,0,0,0.22);
        }

        .barcode-wrapper {
            text-align: center;
            padding: 5px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .barcode-text {
            font-size: 10px;
            margin-bottom: 5px;
            font-family: monospace;
        }

        .bwippbarcode img {
            display: block;
            margin: 0 auto;
            width: auto; /* Use intrinsic width for clarity */
            height: auto; /* Use intrinsic height for clarity */
            image-rendering: crisp-edges;
            image-rendering: -webkit-optimize-contrast;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .icon-btn {
            width: 28px;
            height: 28px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .icon-btn.edit {
            background: #ffc107;
            color: #212529;
        }

        .icon-btn.delete {
            background: #dc3545;
            color: white;
        }

        .icon-btn.print {
            background: #17a2b8;
            color: white;
        }

        .modal {
            position: fixed;
            top: 0; 
            left: 0;
            width: 100vw; 
            height: 100vh;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal.show {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .modal-content {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            background: rgba(139, 0, 0, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            width: 90%;
            max-width: 380px;
            min-width: 320px;
            max-height: 85vh;
            overflow-y: auto;
            margin: 0 auto;
            position: relative;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            animation: modalSlideIn 0.3s ease-out;
        }

        /* Custom scrollbar for modal */
        .modal-content::-webkit-scrollbar {
            width: 8px;
        }

        .modal-content::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .modal-content::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        .modal-content::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-header h3 {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .close-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: #fff;
            font-size: 1.5rem;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .close-btn:hover {
            background: rgba(220, 20, 60, 0.3);
            transform: rotate(90deg);
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #fff;
            font-weight: 500;
            font-size: 0.9rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: #fff;
            font-size: 0.9rem;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        input[type="file"] {
            width: 100%;
            padding: 0.75rem;
            background: transparent;
            border: 2px dashed rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            color: #fff;
            font-size: 0.9rem;
            cursor: pointer;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        input::file-selector-button {
            background: transparent;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="text"]:focus,
        textarea:focus,
        select:focus,
        input[type="file"]:focus {
            outline: none;
            border-color: rgba(220, 20, 60, 0.5);
            box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.1);
            background: rgba(255, 255, 255, 0.15);
        }

        select {
            appearance: none;
            cursor: pointer;
        }

        select option {
            background: #2d0a0a;
            color: #fff;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .submit-btn {
            background: linear-gradient(45deg, #8b0000, #dc143c);
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 20, 60, 0.3);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 20, 60, 0.4);
        }

        .trigger-btn {
            margin: 3rem auto;
            display: block;
            background: linear-gradient(45deg, #8b0000, #dc143c);
            color: #fff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 15px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 6px 20px rgba(220, 20, 60, 0.3);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .trigger-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(220, 20, 60, 0.4);
        }

        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                padding: 1.5rem;
                margin: 1rem;
                max-height: 90vh;
            }
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: rgb(125, 118, 108);
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-category {
            background: #e3f2fd;
            color: #1976d2;
        }

        .badge-type {
            background: #f0f4c3;
            color: #827717;
        }

        .badge-usable {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .badge-unusable {
            background: #ffebee;
            color: #c62828;
        }

        .badge-quantity {
            background: #e3f2fd;
            color: #1976d2;
            font-weight: 600;
        }

        .serial-code {
            background: #f8f9fa;
            padding: 2px 4px;
            border-radius: 3px;
            font-family: monospace;
            font-size: 11px;
        }

        @media print {
            body * { 
                visibility: hidden !important;
            }
            #printContainer, #printContainer * { 
                visibility: visible !important;
            }
            #printContainer {
                position: absolute;
                top: 50px;
                left: 0;
                right: 0;
                margin: auto;
                text-align: center;
                background: white;
                padding: 20px;
            }
            #printContainer .print-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 10px 12px;
                align-items: start;
            }
            #printContainer .barcode-card {
                border: 1px solid #000;
                padding: 8px 6px;
                border-radius: 4px;
                background: #fff;
            }
            .barcode-text {
                font-weight: bold !important;
                font-size: 14px !important;
                margin-bottom: 10px !important;
                font-family: monospace !important;
                color: #000 !important;
            }
            .barcode-wrapper {
                background: white !important;
                border: 2px solid #000 !important;
                padding: 15px !important;
            }
            .bwippbarcode img {
                width: 120px !important; /* Fixed size for uniform print */
                height: 40px !important;
                margin: 0 auto !important;
                image-rendering: crisp-edges !important;
                image-rendering: -webkit-optimize-contrast !important;
            }
        }

        @media (max-width: 768px) {
            .top-buttons {
                flex-direction: column;
            }
            
            table {
                min-width: 800px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 2px;
            }
        }

        .full-set-container {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 15px;
            margin-top: 10px;
            display: none;
        }

        .full-set-header {
            font-weight: bold;
            color: #495057;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .full-set-header i {
            margin-right: 8px;
            color: #007bff;
        }

        .full-set-item {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            margin-bottom: 8px;
        }

        .full-set-item:last-child {
            margin-bottom: 0;
        }

        .full-set-item input {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 14px;
        }

        .full-set-item .item-category {
            min-width: 120px;
            font-weight: 500;
            color: #6c757d;
            margin-right: 12px;
        }

        .set-id-input {
            width: 100px !important;
            text-align: center;
            font-weight: bold;
            color: #007bff;
        }
        
        /* Styles for the new step indicator */
        .step-indicator-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .step-indicator-container::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 2px;
            background: rgba(255, 255, 255, 0.2);
            z-index: 1;
            transform: translateY(-50%);
        }

        .step-indicator {
            width: 30px;
            height: 30px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 2;
            position: relative;
        }
        
        .step-indicator:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .step-indicator.active {
            background: linear-gradient(45deg, #8b0000, #dc143c);
            border-color: #dc143c;
            box-shadow: 0 0 15px rgba(220, 20, 60, 0.5);
        }
        
        .step-indicator.completed {
            background: #28a745;
            border-color: #28a745;
        }

        @media (max-width: 480px) {
            .step-indicator {
                width: 25px;
                height: 25px;
                font-size: 0.9rem;
            }
        }
.room-header {
    padding: 25px 30px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background 0.3s ease;
    user-select: none;
}

.room-header:hover {
    background: linear-gradient(135deg, #5a6fd8, #6a4190);
}

.room-title {
    font-size: 24px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.room-title::before {
    content: "🖥️";
    font-size: 22px;
}

.room-stats {
    display: flex;
    gap: 20px;
    align-items: center;
}

.stat-item {
    background: rgba(255, 255, 255, 0.2);
    padding: 10px 15px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.toggle-icon {
    font-size: 20px;
    transition: transform 0.3s ease;
}

.room-group.expanded .toggle-icon {
    transform: rotate(90deg);
}

.room-content {
    display: none;
    padding: 0;
}

.room-group.expanded .room-content {
    display: block;
}

/* PC Group Styles - Updated to match maintenance.blade.php */
.pc-group {
    margin: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
}

.pc-group:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
}

.pc-header {
    padding: 18px 22px;
    background: linear-gradient(135deg, #34495e, #2c3e50);
    color: white;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background 0.3s ease;
    user-select: none;
}

.pc-header:hover {
    background: linear-gradient(135deg, #2c3e50, #1a252f);
}

.pc-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.pc-title::before {
    content: "💻";
    font-size: 16px;
}

.pc-title small {
    font-size: 12px;
    opacity: 0.8;
    margin-left: 8px;
    font-weight: 400;
    background: rgba(255,255,255,0.2);
    padding: 2px 6px;
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.3);
}

.pc-stats {
    display: flex;
    gap: 12px;
    align-items: center;
}

.pc-stats .stat-item {
    background: rgba(255, 255, 255, 0.2);
    padding: 6px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.pc-content {
    display: none;
    padding: 0;
}

.pc-group.expanded .pc-content {
    display: block;
}

.pc-group.expanded .toggle-icon {
    transform: rotate(90deg);
}

        /* Component-specific styling within PC groups */
        .pc-content .device-category {
            font-weight: 600;
            color: #1976d2;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .pc-content .serial-number {
            font-family: 'Courier New', monospace;
            background: #f5f5f5;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            color: #666;
        }

        .pc-content .barcode-text {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            color: #333;
            font-weight: 500;
        }

        /* PC Group Header Icons */
        .pc-title i {
            font-size: 20px;
            opacity: 0.9;
        }

        /* Enhanced hover effects for PC groups */
        .pc-group:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }

        .pc-group {
            transition: all 0.3s ease;
            position: relative;
        }

        .pc-group::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #2196F3, #1976D2);
            border-radius: 8px 8px 0 0;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .pc-group:not(.collapsed)::before {
            opacity: 1;
        }

/* Full Set Group Styles (Legacy - keeping for compatibility) */
.fullset-group {
    margin: 15px;
    border: 1px solid #f0f0f0;
    border-radius: 6px;
    background: #fafafa;
}

.fullset-header {
    background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
    color: white;
    padding: 12px 15px;
    border-radius: 6px 6px 0 0;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
    user-select: none;
}

.fullset-header:hover {
    background: linear-gradient(135deg, #45a049 0%, #3d8b40 100%);
}

.fullset-title {
    font-size: 16px;
    font-weight: 500;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.fullset-stats {
    display: flex;
    gap: 10px;
    font-size: 13px;
}

.fullset-content {
    max-height: 800px;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background: white;
}

.fullset-group.collapsed .fullset-content {
    max-height: 0;
}

.fullset-group.collapsed .toggle-icon {
    transform: rotate(-90deg);
}

/* Table Styles */
.table-container {
    overflow-x: auto;
}

.table-container table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}

.table-container th,
.table-container td {
    padding: 12px 8px;
    text-align: left;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.table-container th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.table-container tbody tr:hover {
    background-color: #f8f9fa;
}

/* Badges */
.badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.badge-category {
    background-color: #e3f2fd;
    color: #1976d2;
}

.badge-type {
    background-color: #f3e5f5;
    color: #7b1fa2;
}

.badge-usable {
    background-color: #e8f5e8;
    color: #2e7d32;
}

.badge-unusable {
    background-color: #ffebee;
    color: #c62828;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 5px;
}

.icon-btn {
    padding: 6px 8px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
}

.icon-btn.edit {
    background-color: #fff3cd;
    color: #856404;
}

.icon-btn.delete {
    background-color: #f8d7da;
    color: #721c24;
}

.icon-btn.print {
    background-color: #d1ecf1;
    color: #0c5460;
}

.icon-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Serial Code */
.serial-code {
    font-family: 'Courier New', monospace;
    background-color: #f5f5f5;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 12px;
}

/* Barcode Wrapper */
.barcode-wrapper {
    text-align: center;
}

.barcode-text {
    font-size: 10px;
    margin-bottom: 2px;
    color: #666;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px;
    color: #666;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    color: #ccc;
}

.empty-state h3 {
    margin: 0 0 8px 0;
    color: #333;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modal.show {
    display: flex;
    opacity: 1;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background-color: #111827; /* Dark background for better contrast */
    color: #fff;
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.6);
    transform: scale(0.9);
    transition: transform 0.3s ease;
}

.modal.show .modal-content {
    transform: scale(1);
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.15);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #fff;
}

.close-btn {
    background: rgba(255,255,255,0.08);
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #fff;
    padding: 0;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.close-btn:hover {
    background: rgba(255,255,255,0.16);
}

/* Form Styles */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #fff;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 8px;
    color: #fff;
    font-size: 14px;
    box-sizing: border-box;
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: rgba(255,255,255,0.7);
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: rgba(14,165,233,0.6);
    box-shadow: 0 0 0 3px rgba(14,165,233,0.25);
    background: rgba(255,255,255,0.12);
}

.form-group textarea {
    height: 80px;
    resize: vertical;
}

/* Step Indicator */
.step-indicator-container {
    display: flex;
    justify-content: center;
    padding: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.15);
}

.step-indicator {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 10px;
    font-weight: bold;
    transition: all 0.3s ease;
}

.step-indicator.active {
    background-color: #0ea5e9;
}

.step-indicator.completed {
    background-color: #28a745;
}

/* Form Steps */
.form-step {
    padding: 20px;
}

/* Buttons */
.submit-btn,
.add-item-btn {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.2s ease;
}

.submit-btn:hover,
.add-item-btn:hover {
    background-color: #0056b3;
}

.top-buttons {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}

/* Alert Messages */
.alert-message {
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.alert-message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-message button {
    background: none;
    border: none;
    color: inherit;
    cursor: pointer;
    margin-left: auto;
    padding: 0 5px;
    font-weight: bold;
}

/* Full Set Container */
.full-set-container {
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    padding: 15px;
    background-color: #f9f9f9;
    margin-bottom: 20px;
}

.full-set-header {
    font-weight: bold;
    color: #333;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.set-id-input {
    max-width: 100px;
}

.full-set-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    gap: 10px;
}

.item-category {
    min-width: 120px;
    font-weight: 500;
    color: #555;
}

        /* Bulk Delete Styles */
        .bulk-actions {
            background: #f8f9fa;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: none;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .bulk-actions.show {
            display: flex;
        }

        .bulk-actions-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .bulk-actions-right {
            display: flex;
            gap: 10px;
        }

        .selected-count {
            font-weight: 600;
            color: #495057;
        }

        .bulk-delete-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
        }

        .bulk-delete-btn:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .bulk-delete-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }

        .select-all-checkbox {
            margin-right: 10px;
        }

        .item-checkbox {
            margin-right: 10px;
        }

        .room-select-all {
            background: #e9ecef;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            color: #495057;
        }

        .room-select-all input[type="checkbox"] {
            transform: scale(1.2);
        }

        .room-select-all label {
            margin: 0;
            cursor: pointer;
            flex: 1;
        }

        .room-bulk-actions {
            display: flex;
            gap: 10px;
        }

        .room-bulk-delete-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
        }

        .room-bulk-delete-btn:hover {
            background: #c82333;
        }

        .room-bulk-delete-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }

        .room-delete-selected {
            display: flex;
            align-items: center;
        }

        .room-delete-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
        }

        .room-delete-btn:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .room-delete-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }

        .selected-count {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-weight: 600;
        }

        .selected-count .selected-number {
            font-weight: bold;
            color: #ffeb3b;
        }

        .table-container th:first-child,
        .table-container td:first-child {
            width: 40px;
            text-align: center;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .icon-btn {
            padding: 6px 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
        }

        .icon-btn.edit {
            background-color: #fff3cd;
            color: #856404;
        }

        .icon-btn.delete {
            background-color: #f8d7da;
            color: #721c24;
        }

        .icon-btn.print {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .icon-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
</style>
@endpush

@push('scripts')
<!-- Sweet Alert 2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

<div class="main-content">
    <div class="content-wrapper">
        <div class="page-header">
            <h1 class="page-title">Room Management</h1>
            <div class="button-row"></div>
        </div>

        @if(session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745'
                });
            </script>
        @endif

        @if ($errors->any() || session('error'))
            <script>
                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: '{{ session('error') }}',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545'
                    });
                @else
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error!',
                        html: `
                            <ul style="text-align: left; margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        `,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545'
                    });
                @endif
            </script>
        @endif


        <div class="container">
            @php
                // Group items by room_title
                $groupedItems = $items->groupBy('room_title');
            @endphp

            @forelse($groupedItems as $roomTitle => $roomItems)
                @php
                    $totalItems = $roomItems->count();
                    $usableItems = $roomItems->where('status', 'Usable')->count();
                    $unusableItems = $roomItems->where('status', 'Unusable')->count();
                    
                    // Group by PC numbers within this room
                    $pcGroups = [];
                    $individualItems = [];
                    
                    foreach($roomItems as $item) {
                        // Extract PC number from various sources - look for 3-digit number suffix
                        $pcNumber = null;
                        
                        // Try to extract from barcode (SU001, M001, KB001, PC001, etc.)
                        if (preg_match('/(\d{3})$/', $item->barcode, $matches)) {
                            $pcNumber = intval($matches[1]);
                        }
                        // Try to extract from serial number (SU001, M001, KB001, PC001, etc.)
                        elseif (preg_match('/(\d{3})$/', $item->serial_number, $matches)) {
                            $pcNumber = intval($matches[1]);
                        }
                        // Try to extract from full_set_id format: FS-RANDOMID-001 or similar
                        elseif ($item->full_set_id && preg_match('/(\d{3})$/', $item->full_set_id, $matches)) {
                            $pcNumber = intval($matches[1]);
                        }
                        // Try to extract any 3-digit number from barcode/serial
                        elseif (preg_match('/(\d{3})/', $item->barcode . ' ' . $item->serial_number, $matches)) {
                            $pcNumber = intval($matches[1]);
                        }
                        
                        if ($pcNumber !== null) {
                            $pcGroups[$pcNumber][] = $item;
                        } else {
                            // If we can't determine PC number, treat as individual item
                            $individualItems[] = $item;
                        }
                    }
                    
                    // Sort PC groups by number
                    ksort($pcGroups, SORT_NUMERIC);
                    
                    // Debug: Show grouping results
                    // dd($pcGroups, $individualItems);
                    
                    // Debug: Show sample barcodes
                    $sampleBarcodes = $roomItems->take(3)->pluck('barcode', 'device_category')->toArray();
                @endphp

                <div class="room-group" id="room-{{ Str::slug($roomTitle) }}">
                    <div class="room-header" onclick="toggleRoom('{{ Str::slug($roomTitle) }}')">
                        <div class="room-title">
                            {{ $roomTitle }}
                        </div>
                        <div class="room-stats">
                            <div class="stat-item">
                                <i class="fas fa-box"></i>
                                {{ $totalItems }} items
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-hashtag"></i>
                                Qty: 1-{{ $totalItems }}
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-check-circle"></i>
                                {{ $usableItems }} usable
                            </div>
                            @if($unusableItems > 0)
                            <div class="stat-item">
                                <i class="fas fa-times-circle"></i>
                                {{ $unusableItems }} unusable
                            </div>
                            @endif
                            <div class="stat-item selected-count" id="selectedCount-{{ Str::slug($roomTitle) }}" style="display: none;">
                                <i class="fas fa-check-square"></i>
                                <span class="selected-number">0</span> selected
                            </div>
                            <div class="room-delete-selected" style="display: none;">
                                <button class="room-delete-btn" onclick="confirmRoomDelete('{{ Str::slug($roomTitle) }}')" disabled>
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </button>
                            </div>
                            <form method="POST" action="{{ route('room-manage.room-destroy', Str::slug($roomTitle)) }}" onsubmit="return confirmDeleteRoom(event, '{{ addslashes($roomTitle) }}')" style="margin-right:10px;">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="room_title" value="{{ $roomTitle }}">
                                <button type="submit" class="room-delete-btn" title="Delete Room">
                                    <i class="fas fa-trash"></i> Delete Room
                                </button>
                            </form>
                            <div class="toggle-icon">▶</div>
                        </div>
                    </div>

                    <div class="room-content">

                       

                        {{-- PC Groups --}}
                        @php
                            $globalQuantityCounter = 1; // Start continuous numbering
                        @endphp
                        @foreach($pcGroups as $pcNumber => $pcItems)
                            @php
                                $pcUsableCount = collect($pcItems)->where('status', 'Usable')->count();
                                $pcTotalCount = count($pcItems);
                                $displayPcNumber = str_pad($pcNumber, 3, '0', STR_PAD_LEFT);
                                $pcStartQuantity = $globalQuantityCounter; // Track starting quantity for this PC
                                $globalQuantityCounter += $pcTotalCount; // Update global counter
                                
                                // Get unique component types for this PC
                                $componentTypes = collect($pcItems)->pluck('device_category')->unique()->values()->toArray();
                                $componentTypesStr = implode(', ', $componentTypes);
                            @endphp
                            <div class="pc-group" id="pc-{{ Str::slug($roomTitle) }}-{{ $pcNumber }}" data-container="{{ Str::slug($roomTitle) }}-{{ $pcNumber }}">
                                <div class="pc-header" onclick="togglePCGroup('{{ Str::slug($roomTitle) }}-{{ $pcNumber }}')">
                                    <div class="pc-title">
                                        PC{{ $displayPcNumber }}
                                        <small>
                                            ({{ $componentTypesStr }})
                                        </small>
                                    </div>
                                    <div class="pc-stats">
                                        <div class="stat-item">
                                            {{ $pcTotalCount }} components
                                        </div>
                                        <div class="stat-item">
                                            {{ $pcUsableCount }}/{{ $pcTotalCount }} usable
                                        </div>
                                        <div class="stat-item">
                                            <i class="fas fa-hashtag"></i>
                                            Qty: {{ $pcStartQuantity }}-{{ $pcStartQuantity + $pcTotalCount - 1 }}
                                        </div>
                                        <button class="room-delete-btn" style="margin-left:8px;" onclick="openAddComponentModal('{{ addslashes($roomTitle) }}','{{ $displayPcNumber }}', event)"><i class="fas fa-plus"></i> Add Component</button>
                                        <button class="room-delete-btn" style="margin-left:8px; background:#17a2b8;" onclick="printAllBarcodes()"><i class="fas fa-print"></i> Print Barcode</button>
                                        <div class="toggle-icon">▶</div>
                                    </div>
                                </div>

                                <div class="pc-content">
                                    <div class="table-container">
                                        <table class="maintenance-table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <input type="checkbox" class="select-all-checkbox" onchange="toggleSelectAll('{{ Str::slug($roomTitle) }}-{{ $pcNumber }}')">
                                                    </th>
                                                    <th>Photo</th>
                                                    <th>Barcode</th>
                                                    <th>Category</th>
                                                    <th>Brand/Model</th>
                                                    <th>Serial Number</th>
                                                    <th>Description</th>
                                                    <th>Quantity</th>
                                                    <th>Status</th>
                                                    <th>Date Added</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pcItems as $index => $item)
                                                @php
                                                    $continuousQuantity = $pcStartQuantity + $index;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="item-checkbox" data-room="{{ Str::slug($roomTitle) }}" data-item-id="{{ $item->id }}" onchange="updateBulkActions()">
                                                    </td>
                                                        <td>
                                                            @if($item->photo)
                                                                <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=="
                                                                    data-src="{{ route('room-item.photo', $item->id) }}"
                                                                    alt="Item Photo"
                                                                    class="img-thumbnail lazy-img"
                                                                    style="max-width: 40px;"
                                                                    loading="lazy" decoding="async" fetchpriority="low">
                                                            @else
                                                                <img src="{{ asset('path/to/your/placeholder.jpg') }}"
                                                                    alt="Item Photo"
                                                                    class="img-thumbnail"
                                                                    style="max-width: 40px;">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div id="barcode-{{ $item->id }}" class="barcode-wrapper">
                                                                <div class="barcode-text">{{ $item->barcode }}</div>
                                                                <div class="bwippbarcode">
                                                                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item->barcode ?? '000000000', 'C128', 1.6, 40) }}" alt="{{ $item->barcode ?? 'N/A' }}" style="display:block; max-width:none; height:auto;">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="device-category">{{ $item->device_category }}</div>
                                                        </td>
                                                        <td>
                                                            <div class="device-brand-model">
                                                                <strong>{{ $item->brand }}</strong>
                                                                @if($item->model)
                                                                    <br><small>{{ $item->model }}</small>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <code class="serial-number">{{ $item->serial_number }}</code>
                                                        </td>
                                                        <td>
                                                            <div class="device-description">{{ $item->description }}</div>
                                                        </td>
                                                    <td>
                                                        <span class="badge badge-quantity">{{ $continuousQuantity }}</span>
                                                    </td>
                                                        <td>
                                                            <span class="badge {{ $item->status === 'Unusable' ? 'badge-unusable' : 'badge-usable' }}">{{ $item->status ?? 'Not Set' }}</span>
                                                        </td>
                                                        <td>{{ $item->created_at->format('M d, Y') }}</td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <button onclick="openEditModal({{ $item->id }}, '{{ htmlspecialchars($item->room_title, ENT_QUOTES) }}', '{{ htmlspecialchars($item->device_category, ENT_QUOTES) }}', '{{ htmlspecialchars($item->brand ?? '', ENT_QUOTES) }}', '{{ htmlspecialchars($item->model ?? '', ENT_QUOTES) }}', '{{ htmlspecialchars($item->description ?? '', ENT_QUOTES) }}')" class="icon-btn edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <form method="POST" action="/manage-room/item/{{ $item->id }}" style="display:inline;">
                                                                    @csrf @method('DELETE')
                                                                    <button class="icon-btn delete" onclick="confirmDeleteItem({{ $item->id }})">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                                <button onclick="printBarcode({{ $item->id }})" class="icon-btn print">
                                                                    <i class="fas fa-print"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        

                        {{-- Individual Items --}}
                        @if(!empty($individualItems))
                            @php
                                $individualStartQuantity = $globalQuantityCounter; // Continue from PC groups
                            @endphp
                            <div class="table-container" data-container="{{ Str::slug($roomTitle) }}-individual">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" class="select-all-checkbox" onchange="toggleSelectAll('{{ Str::slug($roomTitle) }}-individual')">
                                            </th>
                                            <th>Photo</th>
                                            <th>Category</th>
                                            <th>Brand</th>
                                            <th>Model</th>
                                            <th>Type</th>
                                            <th>Serial #</th>
                                            <th>Description</th>
                                            <th>Barcode</th>
                                            <th>Quantity</th>
                                            <th>Status</th>
                                            <th>Date Added</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($individualItems as $index => $item)
                                        @php
                                            $continuousQuantity = $individualStartQuantity + $index;
                                        @endphp
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="item-checkbox" data-room="{{ Str::slug($roomTitle) }}" data-item-id="{{ $item->id }}" onchange="updateBulkActions()">
                                            </td>
                                            <td>
                                                @if($item->photo)
                                                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=="
                                                        data-src="{{ route('room-item.photo', $item->id) }}"
                                                        alt="Item Photo"
                                                        class="img-thumbnail lazy-img"
                                                        style="max-width: 40px;"
                                                        loading="lazy" decoding="async" fetchpriority="low">
                                                @else
                                                    <img src="{{ asset('path/to/your/placeholder.jpg') }}"
                                                        alt="Item Photo"
                                                        class="img-thumbnail"
                                                        style="max-width: 40px;">
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-category">
                                                    {{ $item->device_category }}
                                                </span>
                                            </td>
                                            <td>{{ $item->brand ?? 'N/A' }}</td>
                                            <td>{{ $item->model ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-type">
                                                    {{ $item->device_type ?? 'Uncategorized' }}
                                                </span>
                                            </td>
                                            <td><span class="serial-code">{{ $item->serial_number }}</span></td>
                                            <td>{{ Str::limit($item->description, 30) }}</td>
                                            <td>
                                                <div id="barcode-{{ $item->id }}" class="barcode-wrapper">
                                                    <div class="barcode-text">{{ $item->barcode }}</div>
                                                    <div class="bwippbarcode">
                                                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item->barcode ?? '000000000', 'C128', 1.6, 40) }}" alt="{{ $item->barcode ?? 'N/A' }}" style="display:block; max-width:none; height:auto;">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-quantity">{{ $continuousQuantity }}</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $item->status === 'Unusable' ? 'badge-unusable' : 'badge-usable' }}">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td>{{ $item->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button onclick="openEditModal({{ $item->id }}, '{{ htmlspecialchars($item->room_title, ENT_QUOTES) }}', '{{ htmlspecialchars($item->device_category, ENT_QUOTES) }}', '{{ htmlspecialchars($item->brand ?? '', ENT_QUOTES) }}', '{{ htmlspecialchars($item->model ?? '', ENT_QUOTES) }}', '{{ htmlspecialchars($item->description ?? '', ENT_QUOTES) }}')" class="icon-btn edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <form method="POST" action="/manage-room/item/{{ $item->id }}" style="display:inline;">
                                                        @csrf @method('DELETE')
                                                        <button class="icon-btn delete" onclick="confirmDeleteItem({{ $item->id }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    <button onclick="printBarcode({{ $item->id }})" class="icon-btn print">
                                                        <i class="fas fa-print"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No items found</h3>
                    <p>Start by adding your first inventory item</p>
                </div>
            @endforelse
        </div>

        <div id="printContainer" style="display:none;"></div>

        {{-- Edit Modal --}}
        <div id="editModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Edit Item</h3>
                    <button class="close-btn" onclick="closeModal('editModal')">×</button>
                </div>
                <div class="step-indicator-container" id="edit-step-indicator">
                    <div class="step-indicator active" data-step="1">1</div>
                    <div class="step-indicator" data-step="2">2</div>
                    <div class="step-indicator" data-step="3">3</div>
                </div>
                <form method="POST" id="editForm" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="form-step" id="edit-step-1" data-step="1">
                        <div class="form-group">
                            <label>Upload New Photo</label>
                            <input type="file" name="photo" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label>Room Title</label>
                            <select name="room_title" id="edit_room_select" onchange="toggleEditCustomRoomInput()" required>
                                <option value="">-- Select Room --</option>
                                <option value="Server">Server</option>
                                <option value="ComLab 1">ComLab 1</option>
                                <option value="ComLab 2">ComLab 2</option>
                                <option value="ComLab 3">ComLab 3</option>
                                <option value="ComLab 4">ComLab 4</option>
                                <option value="ComLab 5">ComLab 5</option>
                                <option value="custom">Other (Custom)</option>
                            </select>
                            <input type="text" name="custom_room_title" id="edit_custom_input" placeholder="Enter custom room title" style="display:none; margin-top: 10px;">
                        </div>
                        <div class="form-group">
                            <label>Device Category</label>
                            <select name="device_category" id="edit_device_category" onchange="toggleEditFullSet()" required>
                                <option value="Full Set">🖥️ Full Set (PC + Peripherals)</option>
                                <option>Keyboard</option>
                                <option>Mouse</option>
                                <option>Monitor</option>
                                <option>Scanner</option>
                                <option>Printer</option>
                                <option>Speakers</option>
                                <option>Webcam</option>
                                <option>Flash Drive</option>
                                <option>Hard Disk Drive</option>
                                <option>Projector</option>
                                <option>Nic</option>
                                <option>Output Devices</option>
                                <option>USB HUB</option>
                                <option>Central Processing Unit</option>
                                <option>CPU</option>
                                <option>Graphics Processing Unit</option>
                                <option>GPU</option>
                                <option>Video Card</option>
                                <option>Random Access Memory</option>
                                <option>RAM</option>
                                <option>Storage Devices</option>
                                <option>Hard Disk Drives</option>
                                <option>HDDs</option>
                                <option>USB Flash Drives</option>
                                <option>External SSDs</option>
                                <option>Motherboard</option>
                                <option>Power Supply Unit</option>
                                <option>PSU</option>
                                <option>System Unit</option>
                            </select>
                        </div>
                        <button type="button" class="submit-btn" onclick="nextEditStep()">Next</button>
                    </div>

                    <div class="form-step" id="edit-step-2" data-step="2" style="display:none;">
                        <div id="edit_fullSetContainer" class="full-set-container" style="display:none;">
                            <div class="full-set-header"><i class="fas fa-desktop"></i> Full Set Configuration</div>
                            <div class="form-group">
                                <label>Set ID Number</label>
                                <input type="text" id="edit_setIdInput" class="set-id-input" placeholder="001" onchange="updateEditFullSetSerials()">
                            </div>
                            <div class="form-group">
                                <label>Brand</label>
                                <input type="text" name="fullset_brand" id="edit_fullset_brand">
                            </div>
                            <div class="form-group">
                                <label>Model</label>
                                <input type="text" name="fullset_model" id="edit_fullset_model">
                            </div>
                            @php $components = [ 
                                ['System Unit', 'pc'], 
                                ['Monitor', 'monitor'], 
                                ['Keyboard', 'keyboard'], 
                                ['Mouse', 'mouse'], 
                                ['Power Supply Unit', 'psu'], 
                                ['SSD', 'ssd'], 
                                ['Motherboard', 'motherboard'], 
                                ['Graphic Card', 'gpu'], 
                                ['RAM', 'ram'], 
                            ]; @endphp 
                            @foreach ($components as [$label, $id])
                            <div class="full-set-item">
                                <span class="item-category">{{ $label }}:</span>
                                <input type="text" id="edit_fullset_{{ $id }}" name="fullset_serials[]" readonly>
                                <input type="hidden" name="fullset_categories[]" value="{{ $label }}">
                            </div>
                            @endforeach
                        </div>
                        <div id="edit_singleItemFields">
                            <div class="form-group">
                                <label>Brand</label>
                                <input type="text" name="brand" id="edit_brand">
                            </div>
                            <div class="form-group">
                                <label>Model</label>
                                <input type="text" name="model" id="edit_model">
                            </div>
                        </div>
                        <div class="top-buttons">
                            <button type="button" class="submit-btn" onclick="prevEditStep()">Back</button>
                            <button type="button" class="submit-btn" onclick="nextEditStep()">Next</button>
                        </div>
                    </div>

                    <div class="form-step" id="edit-step-3" data-step="3" style="display:none;">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" id="edit_description"></textarea>
                        </div>
                        <div class="top-buttons">
                            <button type="button" class="submit-btn" onclick="prevEditStep()">Back</button>
                            <button type="submit" class="submit-btn">Update Item</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Add Item Modal --}}
        <div id="stepModal" class="modal" style="display:none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Add Item</h3>
                    <button class="close-btn" onclick="closeModal('stepModal')">×</button>
                </div>
                <div class="step-indicator-container" id="step-step-indicator">
                    <div class="step-indicator active" data-step="1">1</div>
                    <div class="step-indicator" data-step="2">2</div>
                    <div class="step-indicator" data-step="3">3</div>
                </div>
                <form method="POST" action="/manage-room/item" enctype="multipart/form-data" id="stepItemForm">
                    @csrf
                    <div class="form-step" id="step-1" data-step="1">
                        <div class="form-group">
                            <label>Upload Photo</label>
                            <input type="file" name="photo" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label>Room Title</label>
                            <select name="room_title" id="step_room_title" onchange="toggleStepCustomRoom()" required>
                                <option value="">-- Select Room --</option>
                                <option value="Server">Server</option>
                                <option value="ComLab 1">ComLab 1</option>
                                <option value="ComLab 2">ComLab 2</option>
                                <option value="ComLab 3">ComLab 3</option>
                                <option value="ComLab 4">ComLab 4</option>
                                <option value="ComLab 5">ComLab 5</option>
                                <option value="custom">Other (Custom)</option>
                            </select>
                            <input type="text" name="custom_room_title" id="step_custom_room_input" placeholder="Enter custom room title" style="display:none; margin-top: 10px;">
                        </div>
                        <div class="form-group">
                            <label>Device Category</label>
                            <select name="device_category" id="step_device_category" onchange="toggleStepFullSet()" required>
                                <option value="">-- Select Category --</option>
                                <option value="Full Set">🖥️ Full Set (PC + Peripherals)</option>
                                <option>Keyboard</option>
                                <option>Mouse</option>
                                <option>Monitor</option>
                                <option>Scanner</option>
                                <option>Printer</option>
                                <option>Speakers</option>
                                <option>Webcam</option>
                                <option>Flash Drive</option>
                                <option>Projector</option>
                                <option>Nic</option>
                                <option>Output Devices</option>
                                <option>USB HUB</option>
                                <option>Central Processing Unit</option>
                                <option>Graphics Processing Unit</option>
                                <option>Video Card</option>
                                <option>Random Access Memory</option>
                                <option>RAM</option>
                                <option>Storage Devices</option>
                                <option>Hard Disk Drives</option>
                                <option>USB Flash Drives</option>
                                <option>External SSDs</option>
                                <option>Motherboard</option>
                                <option>Power Supply Unit</option>
                                <option>System Unit</option>
                            </select>
                        </div>
                        <button type="button" class="submit-btn" onclick="nextStep()">Next</button>
                    </div>

                    <div class="form-step" id="step-2" data-step="2" style="display:none;">
                        <div id="step_fullSetContainer" style="display:none;">
                            <div class="full-set-header"><i class="fas fa-desktop"></i> Full Set Configuration</div>
                            <div class="form-group">
                                <label>Set ID Number</label>
                                <input type="text" id="step_setIdInput" class="set-id-input" placeholder="001" onchange="updateStepFullSetSerials()">
                                <small>This will be used as suffix for all items (e.g., PC001, Monitor001)</small>
                            </div>
                            <div class="form-group">
                                <label>Brand</label>
                                <input type="text" name="fullset_brand" placeholder="e.g., Dell">
                            </div>
                            <div class="form-group">
                                <label>Model</label>
                                <input type="text" name="fullset_model" placeholder="e.g., OptiPlex 3080">
                            </div>
                            @php
                                $components = [
                                    ['System Unit', 'pc'],
                                    ['Monitor', 'monitor'],
                                    ['Keyboard', 'keyboard'],
                                    ['Mouse', 'mouse'],
                                    ['Power Supply Unit', 'psu'],
                                    ['SSD', 'ssd'],
                                    ['Motherboard', 'mb'],
                                    ['Graphic Card', 'gpu'],
                                    ['RAM', 'ram'],
                                ];
                            @endphp

                            @foreach ($components as [$label, $id])
                                <div class="form-group">
                                    <label>{{ $label }}</label>
                                    <input type="text" id="step_fullset_{{ $id }}" name="fullset_serials[]" placeholder="{{ strtoupper($id) }}001" readonly>
                                    <input type="hidden" name="fullset_categories[]" value="{{ $label }}">
                                </div>
                            @endforeach
                        </div>

                        <div id="step_singleItemFields">
                            <div class="form-group">
                                <label>Brand</label>
                                <input type="text" name="brand" placeholder="e.g., HP, Logitech">
                            </div>
                            <div class="form-group">
                                <label>Model</label>
                                <input type="text" name="model" placeholder="e.g., MX Master 3">
                            </div>
                        </div>
                        <div class="top-buttons">
                            <button type="button" class="submit-btn" onclick="prevStep()">Back</button>
                            <button type="button" class="submit-btn" onclick="nextStep()">Next</button>
                        </div>
                    </div>

                    <div class="form-step" id="step-3" data-step="3" style="display:none;">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" placeholder="Item details..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="quantity" min="1" value="1" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" required>
                                <option value="">-- Select Status --</option>
                                <option value="Usable">Usable</option>
                                <option value="Unusable">Unusable</option>
                            </select>
                        </div>
                        <div class="top-buttons">
                            <button type="button" class="submit-btn" onclick="prevStep()">Back</button>
                            <button type="submit" class="submit-btn">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Add Component Modal --}}
        <div id="addComponentModal" class="modal" style="display:none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Add Component to <span id="acRoom"></span> - PC<span id="acPc"></span></h3>
                    <button class="close-btn" onclick="closeModal('addComponentModal')">×</button>
                </div>
                <form method="POST" action="#" enctype="multipart/form-data" id="addComponentForm">
                    @csrf
                    <input type="hidden" name="room_title" id="ac_room_title">
                    <div class="form-grid">
                        <div>
                            <label>Device Category</label>
                            <select name="device_category" required>
                                <option value="System Unit">System Unit</option>
                                <option value="Monitor">Monitor</option>
                                <option value="Keyboard">Keyboard</option>
                                <option value="Mouse">Mouse</option>
                                <option value="Speaker">Speaker</option>
                                <option value="SSD">SSD</option>
                                <option value="Motherboard">Motherboard</option>
                                <option value="Graphic Card">Graphic Card</option>
                                <option value="RAM">RAM</option>
                                <option value="Webcam">Webcam</option>
                                <option value="Headset">Headset</option>
                            </select>
                        </div>
                        <div>
                            <label>Brand</label>
                            <input type="text" name="brand" placeholder="Brand">
                        </div>
                        <div>
                            <label>Model</label>
                            <input type="text" name="model" placeholder="Model">
                        </div>
                        <div>
                            <label>Status</label>
                            <select name="status" required>
                                <option value="Usable">Usable</option>
                                <option value="Unusable">Unusable</option>
                            </select>
                        </div>
                        <div>
                            <label>Description</label>
                            <textarea name="description" rows="2" placeholder="Optional"></textarea>
                        </div>
                        <div>
                            <label>Photo (optional)</label>
                            <input type="file" name="photo" accept="image/*">
                        </div>
                    </div>
                    <div style="margin-top:12px; text-align:right; display:flex; gap:8px; justify-content:flex-end;">
                        <button type="button" class="room-delete-btn" onclick="submitAddComponent()">Add Component</button>
                        <button type="button" class="room-delete-btn" style="background:#17a2b8;" onclick="printAllBarcodes()"><i class="fas fa-print"></i> Print Barcode</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Floating Add Item Button -->
<button class="fab-add-item" onclick="toggleStepModal()" aria-label="Add Item" title="Add Item">+</button>

<script>
    let currentStep = 1;
    let currentEditStep = 1;



    // Room Toggle Function
    function toggleRoom(roomSlug) {
        const roomGroup = document.getElementById('room-' + roomSlug);
        roomGroup.classList.toggle('expanded');
        if (roomGroup.classList.contains('expanded')) {
            triggerLazyLoad(roomGroup);
        }
    }

    // PC Group Toggle Function
    function togglePCGroup(pcId) {
        const pcGroup = document.getElementById('pc-' + pcId);
        pcGroup.classList.toggle('expanded');
        if (pcGroup.classList.contains('expanded')) {
            triggerLazyLoad(pcGroup);
        }
    }

    // Full Set Toggle Function (Legacy)
    function toggleFullSet(fullsetId) {
        const fullsetGroup = document.getElementById('fullset-' + fullsetId);
        fullsetGroup.classList.toggle('collapsed');
        if (!fullsetGroup.classList.contains('collapsed')) {
            triggerLazyLoad(fullsetGroup);
        }
    }

    // Step Navigation Functions
    function nextStep() {
        if (currentStep < 3) {
            document.getElementById(`step-${currentStep}`).style.display = 'none';
            currentStep++;
            document.getElementById(`step-${currentStep}`).style.display = 'block';
            updateStepIndicator('step');
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            document.getElementById(`step-${currentStep}`).style.display = 'none';
            currentStep--;
            document.getElementById(`step-${currentStep}`).style.display = 'block';
            updateStepIndicator('step');
        }
    }

    function nextEditStep() {
        if (currentEditStep < 3) {
            document.getElementById(`edit-step-${currentEditStep}`).style.display = 'none';
            currentEditStep++;
            document.getElementById(`edit-step-${currentEditStep}`).style.display = 'block';
            updateStepIndicator('edit');
        }
    }

    function prevEditStep() {
        if (currentEditStep > 1) {
            document.getElementById(`edit-step-${currentEditStep}`).style.display = 'none';
            currentEditStep--;
            document.getElementById(`edit-step-${currentEditStep}`).style.display = 'block';
            updateStepIndicator('edit');
        }
    }

    function updateStepIndicator(modalType) {
        let step = (modalType === 'step') ? currentStep : currentEditStep;
        document.querySelectorAll(`#${modalType}-step-indicator .step-indicator`).forEach(indicator => {
            indicator.classList.remove('active');
            indicator.classList.remove('completed');
            const stepNumber = parseInt(indicator.getAttribute('data-step'));
            if (stepNumber === step) {
                indicator.classList.add('active');
            } else if (stepNumber < step) {
                indicator.classList.add('completed');
            }
        });
    }

    // Modal Functions
    function openEditModal(id, room_title, device_category, brand, model, description) {
        document.getElementById('editForm').action = `/manage-room/item/${id}`;
        const roomSelect = document.getElementById('edit_room_select');
        const customInput = document.getElementById('edit_custom_input');

        let hasMatch = false;
        for (let i = 0; i < roomSelect.options.length; i++) {
            if (roomSelect.options[i].value === room_title) {
                hasMatch = true;
                break;
            }
        }

        if (hasMatch) {
            roomSelect.value = room_title;
            customInput.value = '';
        } else {
            roomSelect.value = 'custom';
            customInput.style.display = 'block';
            customInput.required = true;
            customInput.value = room_title || '';
            roomSelect.name = '';
        }

        document.getElementById('edit_device_category').value = device_category;
        document.getElementById('edit_brand').value = brand || '';
        document.getElementById('edit_model').value = model || '';
        document.getElementById('edit_description').value = description || '';
        
        toggleEditCustomRoomInput();
        toggleEditFullSet();
        openModal('editModal');
        
        currentEditStep = 1;
        document.getElementById('edit-step-1').style.display = 'block';
        document.getElementById('edit-step-2').style.display = 'none';
        document.getElementById('edit-step-3').style.display = 'none';
        updateStepIndicator('edit');
    }

    function toggleStepModal() {
        openModal('stepModal');
        currentStep = 1;
        document.getElementById('step-1').style.display = 'block';
        document.getElementById('step-2').style.display = 'none';
        document.getElementById('step-3').style.display = 'none';
        updateStepIndicator('step');
    }
    
    function openModal(modalId) {
        document.getElementById(modalId).classList.add('show');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
        document.getElementById('editForm').reset();
        document.getElementById('stepItemForm').reset();
        toggleStepCustomRoom();
        toggleStepFullSet();
    }

    function openAddComponentModal(roomTitle, pcNumber, e){
        if(e){ e.stopPropagation(); }
        const modal = document.getElementById('addComponentModal');
        document.getElementById('acRoom').innerText = roomTitle;
        document.getElementById('acPc').innerText = pcNumber;
        document.getElementById('ac_room_title').value = roomTitle;
        const slug = roomTitle.toLowerCase().replace(/\s+/g,'-');
        const form = document.getElementById('addComponentForm');
        form.action = `/manage-room/pc/${slug}/${pcNumber}/component`;
        modal.classList.add('show');
    }

    // Prevent multiple simultaneous operations
    let isOperationInProgress = false;
    
    function submitAddComponent(){
        if (isOperationInProgress) {
            Swal.fire({
                title: 'Please wait',
                text: 'Another operation is in progress. Please wait for it to complete.',
                icon: 'info'
            });
            return;
        }
        
        isOperationInProgress = true;
        const form = document.getElementById('addComponentForm');
        const submitBtn = form.querySelector('button[onclick="submitAddComponent()"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        submitBtn.disabled = true;
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                Swal.fire({
                    title: 'Success!',
                    text: data.message || 'Component added successfully!',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Close modal and reset form
                closeModal('addComponentModal');
                form.reset();
                
                // Reload the page to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(data.message || 'Failed to add component');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: error.message || 'Failed to add component. Please try again.',
                icon: 'error'
            });
        })
        .finally(() => {
            // Reset button state and operation flag
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            isOperationInProgress = false;
        });
    }

    // Custom Room Toggle Functions
    function toggleStepCustomRoom() {
        const roomSelect = document.getElementById('step_room_title');
        const customInput = document.getElementById('step_custom_room_input');
        if (roomSelect.value === 'custom') {
            customInput.style.display = 'block';
            customInput.required = true;
            roomSelect.name = '';
            roomSelect.required = false;
        } else {
            customInput.style.display = 'none';
            customInput.required = false;
            roomSelect.name = 'room_title';
            roomSelect.required = true;
        }
    }

    function toggleEditCustomRoomInput() {
        const roomSelect = document.getElementById('edit_room_select');
        const customInput = document.getElementById('edit_custom_input');
        if (roomSelect.value === 'custom') {
            customInput.style.display = 'block';
            customInput.required = true;
            roomSelect.name = '';
            roomSelect.required = false;
        } else {
            customInput.style.display = 'none';
            customInput.required = false;
            roomSelect.name = 'room_title';
            roomSelect.required = true;
        }
    }

    // Full Set Toggle Functions
    function toggleStepFullSet() {
        const categorySelect = document.getElementById('step_device_category');
        const fullSetContainer = document.getElementById('step_fullSetContainer');
        const singleItemFields = document.getElementById('step_singleItemFields');
        if (categorySelect.value === 'Full Set') {
            fullSetContainer.style.display = 'block';
            singleItemFields.style.display = 'none';
        } else {
            fullSetContainer.style.display = 'none';
            singleItemFields.style.display = 'block';
        }
    }

    function toggleEditFullSet() {
        const categorySelect = document.getElementById('edit_device_category');
        const fullSetContainer = document.getElementById('edit_fullSetContainer');
        const singleItemFields = document.getElementById('edit_singleItemFields');
        if (categorySelect.value === 'Full Set') {
            fullSetContainer.style.display = 'block';
            singleItemFields.style.display = 'none';
        } else {
            fullSetContainer.style.display = 'none';
            singleItemFields.style.display = 'block';
        }
    }

    // Serial Number Generation Functions
    function updateStepFullSetSerials() {
        const suffix = document.getElementById('step_setIdInput').value || '001';
        const components = [
            { id: 'step_fullset_pc', prefix: 'PC' },
            { id: 'step_fullset_monitor', prefix: 'MON' },
            { id: 'step_fullset_keyboard', prefix: 'KEY' },
            { id: 'step_fullset_mouse', prefix: 'MOU' },
            { id: 'step_fullset_psu', prefix: 'PSU' },
            { id: 'step_fullset_ssd', prefix: 'SSD' },
            { id: 'step_fullset_mb', prefix: 'MB' },
            { id: 'step_fullset_gpu', prefix: 'GPU' },
            { id: 'step_fullset_ram', prefix: 'RAM' },
        ];
        
        components.forEach(comp => {
            const input = document.getElementById(comp.id);
            if (input) {
                input.value = `${comp.prefix}${suffix}`;
            }
        });
    }

    function updateEditFullSetSerials() {
        const suffix = document.getElementById('edit_setIdInput').value || '001';
        const components = [
            { id: 'edit_fullset_pc', prefix: 'PC' },
            { id: 'edit_fullset_monitor', prefix: 'MON' },
            { id: 'edit_fullset_keyboard', prefix: 'KEY' },
            { id: 'edit_fullset_mouse', prefix: 'MOU' },
            { id: 'edit_fullset_psu', prefix: 'PSU' },
            { id: 'edit_fullset_ssd', prefix: 'SSD' },
            { id: 'edit_fullset_motherboard', prefix: 'MB' },
            { id: 'edit_fullset_gpu', prefix: 'GPU' },
            { id: 'edit_fullset_ram', prefix: 'RAM' },
        ];
        
        components.forEach(comp => {
            const input = document.getElementById(comp.id);
            if (input) {
                input.value = `${comp.prefix}${suffix}`;
            }
        });
    }
    
    // Print Barcode Function
    function printBarcode(id) {
        const container = document.getElementById('barcode-' + id);
        if (!container) return;
        const labelEl = container.querySelector('.barcode-text');
        const imgEl = container.querySelector('img');
        if (!imgEl) return;

        const label = labelEl ? labelEl.textContent : '';
        const src = imgEl.src;

        let html = '' +
            '<html><head><title>Print Barcode</title>' +
            '<style>' +
            '@page { size: A4; margin: 12mm; }' +
            'body { margin: 0; padding: 0; font-family: Arial, sans-serif; }' +
            '.page { width: 100%; height: 273mm; page-break-after: auto; display: flex; flex-direction: column; }' +
            '.pc-section { flex: 0 0 calc(50% - 8mm); display: flex; flex-direction: column; margin: 4mm 0; border: 1px solid #ccc; padding: 2.5mm; }' +
            '.pc-header { font-weight: bold; font-size: 11px; text-align: center; margin-bottom: 2mm; background: #f7f7f7; padding: 1.8mm; }' +
            '.barcode-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(32mm, 1fr)); gap: 2.5mm; }' +
            '.barcode-card { border: 1px dashed #999; padding: 1.5mm; text-align: center; background: #fff; }' +
            '.barcode-label { font-weight: bold; font-size: 9px; margin-bottom: 1mm; font-family: monospace; color: #000; word-break: break-all; }' +
            '.barcode-img { width: 42mm; height: 14mm; display: block; margin: 0 auto; image-rendering: crisp-edges; image-rendering: -webkit-optimize-contrast; image-rendering: pixelated; }' +
            '@media print { .page { page-break-inside: avoid; } .barcode-card { break-inside: avoid; } }' +
            '</style></head><body>';

        html += '<div class="page">';
        html += '<div class="pc-section">';
        html += '<div class="pc-header">Barcode</div>';
        html += '<div class="barcode-grid">';
        html += '<div class="barcode-card">' +
                    '<div class="barcode-label">' + (label || '') + '</div>' +
                    '<img class="barcode-img" src="' + src + '" />' +
                '</div>';
        html += '</div></div>';
        html += '</div>';

        html += '</body></html>';

        const w = window.open('', '', 'height=800,width=1000');
        if (!w) return;
        w.document.open();
        w.document.write(html);
        w.document.close();
        w.focus();
        w.onload = function(){ w.print(); };
    }

    // Print all barcodes on a single page (grouped by Room and PC)
    function printAllBarcodes() {
        // Ensure any lazy images are loaded within expanded sections
        document.querySelectorAll('.room-group').forEach(group => triggerLazyLoad(group));

        const roomGroups = document.querySelectorAll('.room-group');
        if (!roomGroups.length) return;

        // Collect all PC groups with their barcodes
        let allPCs = [];
        roomGroups.forEach(room => {
            const roomTitleEl = room.querySelector('.room-title');
            const roomTitle = roomTitleEl ? roomTitleEl.textContent.trim() : 'Room';
            
            const pcs = room.querySelectorAll('.pc-group');
            pcs.forEach(pc => {
                const barcodes = pc.querySelectorAll('.barcode-wrapper');
                if (barcodes.length > 0) {
                    // Attempt to derive PC number from first barcode's last 3 digits
                    let pcDisplay = '';
                    const firstLabelEl = barcodes[0].querySelector('.barcode-text');
                    const firstLabel = firstLabelEl ? firstLabelEl.textContent : '';
                    const match = firstLabel.match(/(\d{3})$/);
                    if (match) {
                        pcDisplay = 'PC' + match[1];
                    } else {
                        // Fallback: try reading any number in barcode label
                        const any = firstLabel.match(/(\d{1,3})/);
                        pcDisplay = any ? ('PC' + any[1].padStart(3, '0')) : 'PC';
                    }
                    
                    allPCs.push({
                        roomTitle: roomTitle,
                        pcDisplay: pcDisplay,
                        barcodes: Array.from(barcodes).map(node => {
                            const textEl = node.querySelector('.barcode-text');
                            const imgEl = node.querySelector('img');
                            return {
                                label: textEl ? textEl.textContent : '',
                                src: imgEl ? imgEl.src : ''
                            };
                        }).filter(barcode => barcode.src)
                    });
                }
            });
        });

        // Build printable HTML optimized for 3 PC# per bond paper
        let html = '' +
            '<html><head><title>Print All Barcodes - 3 PC per Page</title>' +
            '<style>' +
            '@page { size: A4; margin: 12mm; }' +
            'body { margin: 0; padding: 0; font-family: Arial, sans-serif; }' +
            '.page { width: 100%; height: 273mm; page-break-after: always; display: flex; flex-direction: column; }' +
            '.page:last-child { page-break-after: auto; }' +
            '.pc-section { flex: 0 0 calc(50% - 8mm); display: flex; flex-direction: column; margin: 4mm 0; border: 1px solid #ccc; padding: 2.5mm; }' +
            '.pc-header { font-weight: bold; font-size: 11px; text-align: center; margin-bottom: 2mm; background: #f7f7f7; padding: 1.8mm; }' +
            '.barcode-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(32mm, 1fr)); gap: 2.5mm; }' +
            '.barcode-card { border: 1px dashed #999; padding: 1.5mm; text-align: center; background: #fff; }' +
            '.barcode-label { font-weight: bold; font-size: 9px; margin-bottom: 1mm; font-family: monospace; color: #000; word-break: break-all; }' +
            '.barcode-img { width: 42mm; height: 14mm; display: block; margin: 0 auto; image-rendering: crisp-edges; image-rendering: -webkit-optimize-contrast; image-rendering: pixelated; }' +
            '@media print { .page { page-break-inside: avoid; } .barcode-card { break-inside: avoid; } }' +
            '</style></head><body>';

        // Group PCs into pages of 3
        for (let i = 0; i < allPCs.length; i += 3) {
            const pagePCs = allPCs.slice(i, i + 3);
            
            html += '<div class="page">';
            
            pagePCs.forEach(pc => {
                html += '<div class="pc-section">';
                html += '<div class="pc-header">' + pc.roomTitle + ' - ' + pc.pcDisplay + '</div>';
                html += '<div class="barcode-grid">';
                
                pc.barcodes.forEach(barcode => {
                    html += '<div class="barcode-card">' +
                                '<div class="barcode-label">' + (barcode.label || '') + '</div>' +
                                '<img class="barcode-img" src="' + barcode.src + '" />' +
                            '</div>';
                });
                
                html += '</div></div>';
            });
            
            html += '</div>';
        }

        html += '</body></html>';

        const w = window.open('', '', 'height=800,width=1000');
        if (!w) return; // popup blocked
        w.document.open();
        w.document.write(html);
        w.document.close();
        w.focus();
        // Give the browser a tick to render images before printing
        w.onload = function(){
            w.print();
        };
    }

    // Event Listeners
    window.onclick = function(event) {
        const editModal = document.getElementById('editModal');
        const stepModal = document.getElementById('stepModal');

        if (event.target === editModal) {
            closeModal('editModal');
        }
        if (event.target === stepModal) {
            closeModal('stepModal');
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal('editModal');
            closeModal('stepModal');
        }
    });

    // Dismiss alert function
    function dismissAlert(button) {
        const alert = button.closest('.alert-message');
        alert.style.opacity = '0';
        alert.style.transform = 'translate(-50%, -50%) scale(0.8)';
        setTimeout(() => {
            alert.remove();
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', (event) => {
        toggleStepCustomRoom();
        toggleEditCustomRoomInput();
        toggleStepFullSet();
        toggleEditFullSet();
        initLazyImages();
        
        // Auto-expand first room for better UX
        const firstRoom = document.querySelector('.room-group');
        if (firstRoom) {
            firstRoom.classList.add('expanded');
            
            // Also expand the first PC within the first room
            const firstPC = firstRoom.querySelector('.pc-group');
            if (firstPC) {
                firstPC.classList.add('expanded');
            }
        }
    });

    // Lazy-load images with IntersectionObserver
    function initLazyImages() {
        const images = document.querySelectorAll('img.lazy-img[data-src]');
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.getAttribute('data-src');
                        img.removeAttribute('data-src');
                        img.classList.remove('lazy-img');
                        obs.unobserve(img);
                    }
                });
            }, { rootMargin: '200px 0px', threshold: 0.01 });

            images.forEach(img => observer.observe(img));
        } else {
            // Fallback: eager swap for older browsers
            images.forEach(img => {
                img.src = img.getAttribute('data-src');
                img.removeAttribute('data-src');
                img.classList.remove('lazy-img');
            });
        }
    }

    function triggerLazyLoad(container) {
        const pending = container.querySelectorAll('img.lazy-img[data-src]');
        pending.forEach(img => {
            // If observer already set, just rely on it; otherwise eager-load now
            if (!('IntersectionObserver' in window)) {
                img.src = img.getAttribute('data-src');
                img.removeAttribute('data-src');
                img.classList.remove('lazy-img');
            }
        });
    }

    // Room-level Selection Functions
    function updateBulkActions() {
        // Update each room individually
        document.querySelectorAll('.room-group').forEach(roomGroup => {
            const roomSlug = roomGroup.id.replace('room-', '');
            const roomCheckboxes = roomGroup.querySelectorAll('.item-checkbox:checked');
            const roomDeleteBtn = roomGroup.querySelector('.room-delete-btn');
            const roomDeleteContainer = roomGroup.querySelector('.room-delete-selected');
            const selectedCountElement = roomGroup.querySelector('.selected-count');
            const selectedNumberElement = roomGroup.querySelector('.selected-number');
            
            if (roomCheckboxes.length > 0) {
                // Show delete button and selected count
                roomDeleteContainer.style.display = 'flex';
                roomDeleteBtn.disabled = false;
                selectedCountElement.style.display = 'flex';
                selectedNumberElement.textContent = roomCheckboxes.length;
            } else {
                // Hide delete button and selected count
                roomDeleteContainer.style.display = 'none';
                roomDeleteBtn.disabled = true;
                selectedCountElement.style.display = 'none';
            }
        });
    }

    function toggleSelectAll(containerId) {
        const container = document.querySelector(`[data-container="${containerId}"]`) || 
                        document.querySelector(`#fullset-${containerId}`) ||
                        document.querySelector(`#room-${containerId.split('-')[0]}`);
        
        if (!container) return;
        
        const checkboxes = container.querySelectorAll('.item-checkbox');
        const selectAllCheckbox = container.querySelector('.select-all-checkbox');
        const isChecked = selectAllCheckbox.checked;
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        
        updateBulkActions();
    }

    function toggleRoomSelectAll(roomSlug) {
        const roomContainer = document.getElementById(`room-${roomSlug}`);
        const checkboxes = roomContainer.querySelectorAll('.item-checkbox');
        const selectAllCheckbox = document.getElementById(`selectAll-${roomSlug}`);
        const isChecked = selectAllCheckbox.checked;
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        
        // Update all select-all checkboxes within this room
        const selectAllCheckboxes = roomContainer.querySelectorAll('.select-all-checkbox');
        selectAllCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        
        updateBulkActions();
    }

    function updateRoomSelectAllStates() {
        document.querySelectorAll('.room-select-all-checkbox').forEach(roomCheckbox => {
            const roomSlug = roomCheckbox.id.replace('selectAll-', '');
            const roomContainer = document.getElementById(`room-${roomSlug}`);
            const checkboxes = roomContainer.querySelectorAll('.item-checkbox');
            const checkedBoxes = roomContainer.querySelectorAll('.item-checkbox:checked');
            
            if (checkboxes.length === 0) {
                roomCheckbox.checked = false;
                roomCheckbox.indeterminate = false;
            } else if (checkedBoxes.length === checkboxes.length) {
                roomCheckbox.checked = true;
                roomCheckbox.indeterminate = false;
            } else if (checkedBoxes.length > 0) {
                roomCheckbox.checked = false;
                roomCheckbox.indeterminate = true;
            } else {
                roomCheckbox.checked = false;
                roomCheckbox.indeterminate = false;
            }
        });
    }


    function confirmRoomDelete(roomSlug) {
        const roomContainer = document.getElementById(`room-${roomSlug}`);
        const checkboxes = roomContainer.querySelectorAll('.item-checkbox:checked');
        
        if (checkboxes.length === 0) return;
        
        const itemIds = Array.from(checkboxes).map(checkbox => checkbox.dataset.itemId);
        
        // Use Sweet Alert for confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${itemIds.length} selected item(s) from this room. This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete them!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                bulkDeleteItems(itemIds);
            }
        });
    }

    function confirmDeleteRoom(e, roomTitle) {
        e.preventDefault();
        Swal.fire({
            title: 'Delete Room?',
            text: `This will permanently delete all items in "${roomTitle}". This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete room',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.submit();
            }
        });
        return false;
    }

    function confirmDeleteItem(itemId) {
        if (isOperationInProgress) {
            Swal.fire({
                title: 'Please wait',
                text: 'Another operation is in progress. Please wait for it to complete.',
                icon: 'info'
            });
            return;
        }
        
        // Use Sweet Alert for confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete this item. This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                isOperationInProgress = true;
                // Show loading state
                Swal.fire({
                    title: 'Deleting item...',
                    text: 'Please wait while we delete the item.',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Use AJAX to delete the item
                fetch(`/manage-room/item/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message || 'Item deleted successfully!',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Reload the page to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        throw new Error(data.message || 'Failed to delete item');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: error.message || 'Failed to delete item. Please try again.',
                        icon: 'error'
                    });
                })
                .finally(() => {
                    isOperationInProgress = false;
                });
            }
        });
    }

    function deleteSelectedRoomItems(roomSlug) {
        if (isOperationInProgress) {
            Swal.fire({
                title: 'Please wait',
                text: 'Another operation is in progress. Please wait for it to complete.',
                icon: 'info'
            });
            return;
        }
        
        const roomContainer = document.getElementById(`room-${roomSlug}`);
        const checkboxes = roomContainer.querySelectorAll('.item-checkbox:checked');
        
        if (checkboxes.length === 0) return;
        
        const itemIds = Array.from(checkboxes).map(checkbox => checkbox.dataset.itemId);
        
        // Use Sweet Alert for confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${itemIds.length} selected item(s) from this room. This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete them!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                isOperationInProgress = true;
                bulkDeleteItems(itemIds);
            }
        });
    }

    function bulkDeleteItems(itemIds) {
        // Show loading state
        Swal.fire({
            title: 'Deleting items...',
            text: 'Please wait while we delete the selected items.',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'DELETE');
        itemIds.forEach(id => {
            formData.append('item_ids[]', id);
        });
        
        fetch('{{ route("room-manage.bulk-destroy") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: data.message || `${itemIds.length} item(s) deleted successfully!`,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Reload the page to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(data.message || 'Failed to delete items');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: error.message || 'Failed to delete items. Please try again.',
                icon: 'error'
            });
        })
        .finally(() => {
            isOperationInProgress = false;
        });
    }
</script>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rawMessage = @json(session('success')) || '';
        const normalized = String(rawMessage).toLowerCase();
        let displayText = rawMessage;

        // Map backend success messages to the requested SweetAlert texts
        if (normalized.includes('delete')) {
            displayText = 'Successfully Deleted Data';
        } else if (normalized.includes('save') || normalized.includes('added') || normalized.includes('create')) {
            displayText = 'Successfully Added Data';
        }

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: displayText,
                showConfirmButton: false,
                timer: 1800
            });
        }
    });
</script>
@endif

@endsection

