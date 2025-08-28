<?php use Milon\Barcode\Facades\DNS1DFacade as DNS1D; ?>

@extends('layouts.app')
@section('title', 'Room Management')
@section('content')

@push('styles')
<style>
/* Room Group Styles */
.room-group {
    margin-bottom: 30px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

        /* Updated styling for success and error messages */
        .alert-message {
            padding: 10px;
            margin: 15px auto; /* Centering the alert message */
            border-radius: 4px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 500px; /* Limit width for better appearance */
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .alert-message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
            width: 70px; /* Adjusted for smaller display */
            height: 20px; /* Adjusted for smaller display */
            display: block;
            margin: 0 auto;
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
                width: 150px !important; /* Adjusted for smaller print size */
                height: 50px !important; /* Adjusted for smaller print size */
                margin: 0 auto !important;
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 8px 8px 0 0;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
    user-select: none;
}

.room-header:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}

.room-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.room-stats {
    display: flex;
    gap: 15px;
    font-size: 14px;
}

.stat-item {
    background: rgba(255,255,255,0.2);
    padding: 4px 12px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.toggle-icon {
    transition: transform 0.3s ease;
    font-size: 20px;
}

.room-group.collapsed .toggle-icon {
    transform: rotate(-90deg);
}

.room-content {
    max-height: 1000px;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.room-group.collapsed .room-content {
    max-height: 0;
}

/* Full Set Group Styles */
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
    background-color: #fff;
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    transform: scale(0.9);
    transition: transform 0.3s ease;
}

.modal.show .modal-content {
    transform: scale(1);
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #333;
}

.close-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #999;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.close-btn:hover {
    color: #333;
}

/* Form Styles */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    box-sizing: border-box;
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
    border-bottom: 1px solid #eee;
}

.step-indicator {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #ddd;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 10px;
    font-weight: bold;
    transition: all 0.3s ease;
}

.step-indicator.active {
    background-color: #007bff;
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
</style>
@endpush

<div class="main-content">
    <div class="content-wrapper">
        <div class="page-header">
            <h1 class="page-title">Room Management</h1>
            <div class="button-row">
                <button onclick="toggleStepModal()" class="add-item-btn">
                    <i class="fas fa-plus-circle"></i> Add Item
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-message success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button onclick="this.parentNode.style.display='none'">Okay</button>
            </div>
        @endif

        @if ($errors->any() || session('error'))
            <div class="alert-message error">
                <i class="fas fa-times-circle"></i>
                @if (session('error'))
                    <p>{{ session('error') }}</p>
                @endif
                @if ($errors->any())
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
                <button onclick="this.parentNode.style.display='none'">Okay</button>
            </div>
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
                    
                    // Group by full sets within this room
                    $fullSets = [];
                    $individualItems = [];
                    
                    foreach($roomItems as $item) {
                        if($item->device_category === 'Full Set') {
                            // Extract set number from serial_number (assuming format like PC001, MON001, etc.)
                            preg_match('/\d+$/', $item->serial_number, $matches);
                            $setNumber = $matches[0] ?? '001';
                            $fullSets[$setNumber][] = $item;
                        } else {
                            $individualItems[] = $item;
                        }
                    }
                @endphp

                <div class="room-group" id="room-{{ Str::slug($roomTitle) }}">
                    <div class="room-header" onclick="toggleRoom('{{ Str::slug($roomTitle) }}')">
                        <div class="room-title">
                            <i class="fas fa-door-open"></i>
                            {{ $roomTitle }}
                        </div>
                        <div class="room-stats">
                            <div class="stat-item">
                                <i class="fas fa-box"></i>
                                {{ $totalItems }} items
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
                            <div class="toggle-icon">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    <div class="room-content">
                        {{-- Full Sets --}}
                        @foreach($fullSets as $setNumber => $setItems)
                            @php
                                $setUsableCount = collect($setItems)->where('status', 'Usable')->count();
                                $setTotalCount = count($setItems);
                            @endphp
                            <div class="fullset-group" id="fullset-{{ Str::slug($roomTitle) }}-{{ $setNumber }}">
                                <div class="fullset-header" onclick="toggleFullSet('{{ Str::slug($roomTitle) }}-{{ $setNumber }}')">
                                    <div class="fullset-title">
                                        <i class="fas fa-desktop"></i>
                                        Full Set {{ $setNumber }}
                                    </div>
                                    <div class="fullset-stats">
                                        <div class="stat-item">
                                            {{ $setTotalCount }} components
                                        </div>
                                        <div class="stat-item">
                                            {{ $setUsableCount }}/{{ $setTotalCount }} usable
                                        </div>
                                        <div class="toggle-icon">
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="fullset-content">
                                    <div class="table-container">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Photo</th>
                                                    <th>Component</th>
                                                    <th>Brand</th>
                                                    <th>Model</th>
                                                    <th>Serial #</th>
                                                    <th>Description</th>
                                                    <th>Barcode</th>
                                                    <th>Status</th>
                                                    <th>Date Added</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($setItems as $item)
                                                <tr>
                                                    <td>
                                                        @if($item->photo)
                                                            <img src="{{ route('room-item.photo', $item->id) }}"
                                                                alt="Item Photo"
                                                                class="img-thumbnail"
                                                                style="max-width: 40px;">
                                                        @else
                                                            <img src="{{ asset('path/to/your/placeholder.jpg') }}"
                                                                alt="Item Photo"
                                                                class="img-thumbnail"
                                                                style="max-width: 40px;">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <strong>{{ $item->device_type ?? 'Component' }}</strong>
                                                    </td>
                                                    <td>{{ $item->brand ?? 'N/A' }}</td>
                                                    <td>{{ $item->model ?? 'N/A' }}</td>
                                                    <td><span class="serial-code">{{ $item->serial_number }}</span></td>
                                                    <td>{{ Str::limit($item->description, 30) }}</td>
                                                    <td>
                                                        <div id="barcode-{{ $item->id }}" class="barcode-wrapper">
                                                            <div class="barcode-text">{{ $item->barcode }}</div>
                                                            <div class="bwippbarcode">
                                                                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item->barcode ?? '000000000', 'C128', 1.5, 30) }}" alt="{{ $item->barcode ?? 'N/A' }}" style="display:block; max-width:100%; height:auto;">
                                                            </div>
                                                        </div>
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
                                                                <button class="icon-btn delete" onclick="return confirm('Delete this item?')">
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
                            <div class="table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Photo</th>
                                            <th>Category</th>
                                            <th>Brand</th>
                                            <th>Model</th>
                                            <th>Type</th>
                                            <th>Serial #</th>
                                            <th>Description</th>
                                            <th>Barcode</th>
                                            <th>Status</th>
                                            <th>Date Added</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($individualItems as $item)
                                        <tr>
                                            <td>
                                                @if($item->photo)
                                                    <img src="{{ route('room-item.photo', $item->id) }}"
                                                        alt="Item Photo"
                                                        class="img-thumbnail"
                                                        style="max-width: 40px;">
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
                                                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item->barcode ?? '000000000', 'C128', 1.5, 30) }}" alt="{{ $item->barcode ?? 'N/A' }}" style="display:block; max-width:100%; height:auto;">
                                                    </div>
                                                </div>
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
                                                        <button class="icon-btn delete" onclick="return confirm('Delete this item?')">
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
    </div>
</div>

<script>
    let currentStep = 1;
    let currentEditStep = 1;

    // Room Toggle Function
    function toggleRoom(roomSlug) {
        const roomGroup = document.getElementById('room-' + roomSlug);
        roomGroup.classList.toggle('collapsed');
    }

    // Full Set Toggle Function
    function toggleFullSet(fullsetId) {
        const fullsetGroup = document.getElementById('fullset-' + fullsetId);
        fullsetGroup.classList.toggle('collapsed');
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
        const barcodeContainer = document.getElementById('barcode-' + id);
        if (barcodeContainer) {
            const printContent = `<div style="text-align:center; padding:20px;">` +
                `<div style="font-weight:bold; font-size:14px; margin-bottom:10px;">${barcodeContainer.querySelector('.barcode-text').textContent}</div>` +
                `<img src="${barcodeContainer.querySelector('img').src}" style="width:150px; height:50px; display:block; margin:0 auto;" />` +
                `</div>`;
            const printWindow = window.open('', '', 'height=400,width=600');
            printWindow.document.write('<html><head><title>Print Barcode</title>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }
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

    document.addEventListener('DOMContentLoaded', (event) => {
        toggleStepCustomRoom();
        toggleEditCustomRoomInput();
        toggleStepFullSet();
        toggleEditFullSet();
        
        // Initialize all rooms as expanded by default
        // If you want them collapsed by default, uncomment the lines below:
        // document.querySelectorAll('.room-group').forEach(group => {
        //     group.classList.add('collapsed');
        // });
        
        // Initialize all full sets as expanded by default
        // If you want them collapsed by default, uncomment the lines below:
        // document.querySelectorAll('.fullset-group').forEach(group => {
        //     group.classList.add('collapsed');
        // });
    });
</script>

@endsection

