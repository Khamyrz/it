@extends('layouts.app')
@section('title', 'Borrow')
@section('content')
@push('styles')
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
            gap: 20px;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: clamp(24px, 5vw, 32px);
            color: #2c3e50;
            margin: 0;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title::before {
            content: "📋";
            font-size: clamp(20px, 4vw, 28px);
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
            padding: 8px 16px;
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

        input, select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background: white;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .submit-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
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
    </style>
    
</head>
<body>

<div class="main-content">
    <div class="content-wrapper">
        <div class="page-header">
            <h1 class="page-title">Borrowed Items List</h1>
            <div class="top-buttons">
                <button onclick="openBorrowModal()"><i class="fas fa-plus"></i> Borrow Item</button>
                <button onclick="openTrackerModal()"><i class="fas fa-calendar-alt"></i> Monthly Tracker</button>
            </div>
        </div>

        <!-- Success Message (keeping original PHP logic) -->
        @if (session('success'))
            <div class="success-message">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
    </div>

    <!-- Desktop Table View - Edge to Edge -->
    <div class="table-container">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-door-open"></i> Room</th>
                        <th><i class="fas fa-tags"></i> Category</th>
                        <th><i class="fas fa-barcode"></i> Serial #</th>
                        <th><i class="fas fa-info-circle"></i> Description</th>
                        <th><i class="fas fa-check-circle"></i> Status</th>
                        <th><i class="fas fa-user"></i> Borrower</th>
                        <th><i class="fas fa-calendar"></i> Borrow Date</th>
                        <th><i class="fas fa-undo"></i> Return</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td><strong>{{ $item->room_title }}</strong></td>
                            <td>{{ $item->device_category }}</td>
                            <td><code>{{ $item->serial_number }}</code></td>
                            <td>{{ $item->description }}</td>
                            <td>
                                @if($item->status === 'Unusable')
                                    <span class="unusable">❌ Unusable</span>
                                @elseif($item->borrow && $item->borrow->status === 'Borrowed')
                                    <span class="status-badge status-borrowed">Borrowed</span>
                                @else
                                    <span class="status-badge status-usable">Usable</span>
                                @endif
                            </td>
                            <td>{{ $item->latestBorrow->borrower_name ?? '-' }}</td>

                            <td>
                               @if($item->latestBorrow && $item->latestBorrow->borrow_date)
        <div class="date-info">
            <span class="date-main">{{ \Carbon\Carbon::parse($item->latestBorrow->borrow_date)->format('M d, Y (g:i A)') }}</span>
            <span class="date-relative">{{ \Carbon\Carbon::parse($item->latestBorrow->borrow_date)->diffForHumans() }}</span>
        </div>
    @else
        -
    @endif

                            </td>
                            <td>
                                @if($item->latestBorrow && $item->latestBorrow->status === 'Borrowed')
        <form method="POST" action="/borrow/return/{{ $item->latestBorrow->id }}">
            @csrf
            <button class="btn-return"><i class="fas fa-check"></i> Return</button>
        </form>
    @elseif($item->status === 'Unusable')
        <button class="btn-return btn-disabled" disabled><i class="fas fa-times"></i> Not Usable</button>
    @else
        <button class="btn-return btn-disabled" disabled><i class="fas fa-check"></i> Returned</button>
    @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-state-icon">📭</div>
                                    <p>No items found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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

<!-- Modal: Borrow Item (keeping original functionality) -->
<div id="borrowModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeBorrowModal()">&times;</span>
        <h3>➕ Borrow Item</h3>

        <form method="POST" action="/borrow">
            @csrf
            <div class="form-group">
                <label for="room_item_id">Select Item</label>
                <select name="room_item_id" required>
                    <option value="">-- Choose an item --</option>
                    @foreach($availableItems as $item)
                        <option value="{{ $item->id }}">
                            [{{ $item->room_title }}] - {{ $item->device_category }} - {{ $item->serial_number }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="borrower_name">Borrower Name</label>
                <input type="text" name="borrower_name" required>
            </div>

            <div class="form-group">
                <label for="borrow_date">Borrow Date</label>
                <input type="datetime-local" name="borrow_date" required>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-check"></i> Submit Borrow Request
            </button>
        </form>
    </div>
</div>

<!-- Modal: Monthly Tracker (keeping original functionality) -->
<div id="trackerModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeTrackerModal()">&times;</span>
        <h3>📅 Monthly Activity Tracker</h3>
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
                            <td>{{ $activity->borrower_name }}</td>
                            <td><code>{{ $activity->item->serial_number ?? 'N/A' }}</code></td>
                            <td>{{ $activity->item->device_category ?? 'N/A' }}</td>
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

<script>
    // Original JavaScript functionality preserved
    function openBorrowModal() {
        document.getElementById("borrowModal").style.display = "block";
        document.body.style.overflow = "hidden"; // Prevent background scrolling
    }

    function closeBorrowModal() {
        document.getElementById("borrowModal").style.display = "none";
        document.body.style.overflow = "auto";
    }

    function openTrackerModal() {
        document.getElementById("trackerModal").style.display = "block";
        document.body.style.overflow = "hidden";
    }

    function closeTrackerModal() {
        document.getElementById("trackerModal").style.display = "none";
        document.body.style.overflow = "auto";
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
</script>
@endsection