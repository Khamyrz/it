@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        display: flex;
        height: 100vh;
        background: #f4f6f8;
    }
    nav {
        width: 250px;
        background: #2c3e50;
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 20px;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    }
    nav img {
        width: 100px;
        margin-bottom: 10px;
    } 
    nav h2 {
        font-size: 16px;
        margin: 0 0 20px;
    }
    nav ul {
        list-style: none;
        padding: 0;
        width: 100%;
    }
    nav ul li {
        width: 100%;
    }
    nav ul li a {
        text-decoration: none;
        color: white;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: background 0.3s ease;
    }
    nav ul li a:hover {
        background: #34495e;
    }
    .container {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 12px;
        max-width: 1200px;
        margin: auto;
    }
    .box {
        background: white;
        padding: 12px;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        margin-bottom: 12px;
    }
    .category-total {
        background: #e3f2fd;
        border-left: 4px solid #2196f3;
        padding: 6px 10px;
        margin: 4px 0;
        border-radius: 4px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .category-total .category-name {
        font-weight: 600;
        color: #1976d2;
        font-size: 13px;
    }
    .category-total .category-count {
        background: #2196f3;
        color: white;
        padding: 3px 6px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
    }
    .totals-section {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #e9ecef;
    }
    .totals-title {
        font-size: 14px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .no-totals {
        color: #6c757d;
        font-style: italic;
        text-align: center;
        padding: 20px;
    }

    /* Responsive Mobile Design */
    @media (max-width: 768px) {
        body {
            flex-direction: column;
        }

        nav {
            width: 100%;
            height: auto;
            position: relative;
            padding: 15px;
        }

        nav ul {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        nav ul li {
            width: auto;
        }

        nav ul li a {
            padding: 8px 12px;
            font-size: 14px;
        }

        .container {
            padding: 15px;
            max-width: 100%;
        }

        .box {
            padding: 15px;
            margin-bottom: 15px;
        }

        .row {
            flex-direction: column;
        }

        .col-md-6 {
            width: 100%;
            margin-bottom: 20px;
        }

        table {
            font-size: 12px;
            display: block;
            overflow-x: auto;
        }

        table thead {
            display: none;
        }

        table tbody tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background: white;
        }

        table tbody td {
            display: block;
            text-align: right;
            padding: 8px;
            border: none;
            border-bottom: 1px solid #f1f3f4;
        }

        table tbody td:before {
            content: attr(data-label);
            float: left;
            font-weight: 600;
            color: #495057;
        }

        table tbody td:last-child {
            border-bottom: none;
        }

        .btn {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px 10px;
            font-size: 13px;
        }

        .modal-dialog {
            max-width: 95%;
            margin: 10px;
        }

        .modal-content {
            padding: 15px;
        }

        .page-title {
            font-size: 20px;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding: 10px;
        }

        .box {
            padding: 10px;
        }

        table {
            font-size: 11px;
        }

        .btn {
            padding: 6px 10px;
            font-size: 12px;
        }
    }
</style>

<div class="container">
    <!-- SQL Export Section -->
    <div class="box" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div>
                <h4 style="margin: 0; color: white; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-database"></i> Database Export
                </h4>
                <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">
                    Export system data as SQL file. Automatic exports run every hour.
                </p>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button id="exportSqlBtn" class="btn btn-light" onclick="exportSql()" style="font-weight: 600;">
                    <i class="fas fa-download"></i> Export SQL Now
                </button>
                <button id="toggleAutoExportBtn" class="btn btn-outline-light" onclick="toggleAutoExport()" style="font-weight: 600;">
                    <i class="fas fa-clock"></i> <span id="autoExportStatus">Enable</span> Auto Export
                </button>
            </div>
        </div>
        <div id="exportStatus" style="margin-top: 15px; padding: 10px; background: rgba(255,255,255,0.1); border-radius: 5px; display: none;">
            <small id="exportStatusText"></small>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 box">
            <h4 style="font-size: 16px; margin-bottom: 12px;">ALL CATEGORIES (from Room Items)</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Items Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roomItemCategories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $category }}</td>
                            <td>{{ $itemCounts[$category] ?? 0 }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#itemModal" onclick="loadItems('{{ $category }}', 'category')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-6 box">
            <h4 style="font-size: 16px; margin-bottom: 12px;">ALL ROOMS</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Room</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $index => $room)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $room->room_title }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#itemModal" onclick="loadItems('{{ $room->room_title }}', 'room')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Enhanced Modal -->
<div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemModalLabel">Item Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Category Totals Section -->
                <div id="categoryTotalsSection" style="display: none;">
                    <div class="totals-section">
                        <div class="totals-title">
                            <i class="fas fa-chart-bar"></i>
                            <span id="roomTotalTitle">Category Totals</span>
                        </div>
                        <div id="categoryTotalsList">
                            <!-- Category totals will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Room</th>
                            <th>Category</th>
                            <th>Serial</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="modalItemList">
                        <tr><td colspan="5">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function loadItems(identifier, type) {
        // Update modal title
        const modalTitle = document.getElementById('itemModalLabel');
        modalTitle.textContent = type === 'room' ? `Room: ${identifier}` : `Category: ${identifier}`;
        
        // Show/hide category totals section based on type
        const categoryTotalsSection = document.getElementById('categoryTotalsSection');
        const roomTotalTitle = document.getElementById('roomTotalTitle');
        
        if (type === 'room') {
            categoryTotalsSection.style.display = 'block';
            roomTotalTitle.textContent = `${identifier} - Category Totals`;
        } else {
            categoryTotalsSection.style.display = 'none';
        }

        fetch(`/categories/items/${encodeURIComponent(identifier)}?type=${type}`)
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('modalItemList');
                list.innerHTML = '';
                
                // Load category totals if it's a room
                if (type === 'room' && data.categoryTotals) {
                    loadCategoryTotals(data.categoryTotals);
                }
                
                if (data.items.length === 0) {
                    list.innerHTML = '<tr><td colspan="5">No items found.</td></tr>';
                } else {
                    data.items.forEach(item => {
                        list.innerHTML += `
                            <tr>
                                <td><img src="/storage/${item.photo}" width="50" height="50" onerror="this.src='/default.png'" /></td>
                                <td>${item.room_title}</td>
                                <td>${item.device_category}</td>
                                <td>${item.serial_number}</td>
                                <td><span class="badge ${getStatusBadgeClass(item.status)}">${item.status}</span></td>
                            </tr>`;
                    });
                }
            })
            .catch(error => {
                console.error('Error loading items:', error);
                document.getElementById('modalItemList').innerHTML = '<tr><td colspan="5">Error loading items.</td></tr>';
                
                // Hide totals section on error
                document.getElementById('categoryTotalsSection').style.display = 'none';
            });
    }

    function loadCategoryTotals(categoryTotals) {
        const totalsList = document.getElementById('categoryTotalsList');
        totalsList.innerHTML = '';
        
        if (!categoryTotals || Object.keys(categoryTotals).length === 0) {
            totalsList.innerHTML = '<div class="no-totals">No items found in this room.</div>';
            return;
        }
        
        // Sort categories by count (descending)
        const sortedCategories = Object.entries(categoryTotals)
            .sort(([,a], [,b]) => b - a);
        
        sortedCategories.forEach(([category, count]) => {
            const categoryDiv = document.createElement('div');
            categoryDiv.className = 'category-total';
            categoryDiv.innerHTML = `
                <span class="category-name">${category}</span>
                <span class="category-count">${count}</span>
            `;
            totalsList.appendChild(categoryDiv);
        });
    }

    function getStatusBadgeClass(status) {
        switch(status.toLowerCase()) {
            case 'active':
            case 'working':
                return 'bg-success';
            case 'inactive':
            case 'broken':
                return 'bg-danger';
            case 'maintenance':
                return 'bg-warning';
            default:
                return 'bg-secondary';
        }
    }

    // SQL Export Functions
    function exportSql() {
        const btn = document.getElementById('exportSqlBtn');
        const statusDiv = document.getElementById('exportStatus');
        const statusText = document.getElementById('exportStatusText');
        
        // Disable button and show loading
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
        statusDiv.style.display = 'block';
        statusText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating SQL export...';
        statusDiv.style.background = 'rgba(255,255,255,0.2)';
        
        // Make request to export endpoint
        fetch('/categories/export-sql', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => {
            if (response.ok) {
                return response.blob();
            }
            throw new Error('Export failed');
        })
        .then(blob => {
            // Create download link
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `database_export_${new Date().toISOString().split('T')[0]}_${Date.now()}.sql`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            
            // Show success message
            statusText.innerHTML = '<i class="fas fa-check-circle"></i> SQL file exported successfully!';
            statusDiv.style.background = 'rgba(40, 167, 69, 0.3)';
            
            // Re-enable button
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-download"></i> Export SQL Now';
            
            // Hide status after 5 seconds
            setTimeout(() => {
                statusDiv.style.display = 'none';
            }, 5000);
        })
        .catch(error => {
            console.error('Export error:', error);
            statusText.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Export failed. Please try again.';
            statusDiv.style.background = 'rgba(220, 53, 69, 0.3)';
            
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-download"></i> Export SQL Now';
            
            setTimeout(() => {
                statusDiv.style.display = 'none';
            }, 5000);
        });
    }

    function toggleAutoExport() {
        const btn = document.getElementById('toggleAutoExportBtn');
        const statusSpan = document.getElementById('autoExportStatus');
        
        btn.disabled = true;
        const currentText = statusSpan.textContent.trim();
        const isEnabled = currentText === 'Disable';
        
        fetch('/categories/toggle-auto-export', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ enabled: !isEnabled })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                statusSpan.textContent = data.enabled ? 'Disable' : 'Enable';
                const statusDiv = document.getElementById('exportStatus');
                const statusText = document.getElementById('exportStatusText');
                statusDiv.style.display = 'block';
                statusText.innerHTML = data.enabled 
                    ? '<i class="fas fa-check-circle"></i> Automatic exports enabled. Exports will run every hour.'
                    : '<i class="fas fa-info-circle"></i> Automatic exports disabled.';
                statusDiv.style.background = data.enabled ? 'rgba(40, 167, 69, 0.3)' : 'rgba(255,255,255,0.2)';
                
                setTimeout(() => {
                    statusDiv.style.display = 'none';
                }, 5000);
            }
            btn.disabled = false;
        })
        .catch(error => {
            console.error('Toggle error:', error);
            btn.disabled = false;
        });
    }

    // Check auto-export status on page load
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/categories/auto-export-status')
            .then(response => response.json())
            .then(data => {
                if (data.enabled) {
                    document.getElementById('autoExportStatus').textContent = 'Disable';
                }
            })
            .catch(error => console.error('Status check error:', error));
    });
</script>
@endsection