@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .page-wrap { padding: 28px; background: #f4f6f8; min-height: 100vh; }
    .page-header { display:flex; align-items:center; justify-content:space-between; gap:12px; margin: 0 0 16px 0; }
    .page-title { display:flex; align-items:center; gap:10px; margin:0; font-weight:700; color:#1f2937; }
    .page-title .icon { width:40px; height:40px; display:grid; place-items:center; border-radius:10px; background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; }
    .card { background:#fff; border:1px solid #eef0f4; border-radius:14px; box-shadow: 0 8px 24px rgba(16,24,40,0.06); padding: 16px; }
    .card-header { display:flex; align-items:center; justify-content:space-between; gap:12px; margin: 0 0 10px 0; }
    .search { position:relative; }
    .search input { border:1px solid #e5e7eb; border-radius:10px; padding:10px 36px 10px 36px; outline:none; width:100%; }
    .search .fa-search { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#9ca3af; }
    .table { margin:0; }
    .table thead th { background:#f9fafb; color:#374151; font-weight:700; border-bottom:1px solid #eef0f4; }
    .table tbody tr:hover { background:#fafafa; }
    .badge-soft { background:#eef2ff; color:#4f46e5; padding:6px 10px; border-radius:999px; font-weight:700; font-size:12px; }
    .btn-icon { border:none; border-radius:10px; padding:8px 10px; background:#eef2ff; color:#4f46e5; }
    .btn-icon:hover { background:#e0e7ff; }
    .muted { color:#6b7280; font-size:13px; }
    .category-total { background:#f8fafc; border:1px solid #eef2f7; border-radius:10px; padding:10px 12px; display:flex; justify-content:space-between; align-items:center; margin:6px 0; }
    .category-total .category-name { font-weight:700; color:#0f172a; }
    .category-total .category-count { background:#0ea5e9; color:#fff; padding:4px 10px; border-radius:999px; font-weight:700; font-size:12px; }
    .no-totals { color:#6b7280; font-style:italic; text-align:center; padding:16px; }
    @media (max-width: 992px) { .card { margin-bottom: 12px; } }
</style>

<div class="page-wrap">
    <div class="page-header">
        <h3 class="page-title"><span class="icon"><i class="fa fa-tags"></i></span> Categories & Rooms</h3>
        <div class="muted">Browse device categories and rooms. Click the eye icon to inspect items.</div>
    </div>
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div style="font-weight:700;">All Categories</div>
                    <div class="search" style="max-width: 260px; width:100%;">
                        <i class="fa fa-search"></i>
                        <input id="filterCategories" type="text" placeholder="Search categories">
                    </div>
                </div>
                <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th class="text-center">Items</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roomItemCategories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="category-name-cell">{{ $category }}</td>
                            <td class="text-center"><span class="badge-soft">{{ $itemCounts[$category] ?? 0 }}</span></td>
                            <td>
                                <button class="btn-icon" title="View items" data-bs-toggle="modal" data-bs-target="#itemModal" onclick="loadItems('{{ $category }}', 'category')"><i class="fa fa-eye"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div style="font-weight:700;">All Rooms</div>
                    <div class="search" style="max-width: 260px; width:100%;">
                        <i class="fa fa-search"></i>
                        <input id="filterRooms" type="text" placeholder="Search rooms">
                    </div>
                </div>
                <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Room Title</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $index => $room)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="room-title-cell">{{ $room->room_title }}</td>
                            <td>
                                <button class="btn-icon" title="View items" data-bs-toggle="modal" data-bs-target="#itemModal" onclick="loadItems('{{ $room->room_title }}', 'room')"><i class="fa fa-eye"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
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
                <table class="table table-striped align-middle">
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
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td><img src="/storage/${item.photo}" width="50" height="50" style="border-radius:8px; object-fit:cover;" onerror="this.src='/default.png'" /></td>
                            <td>${item.room_title}</td>
                            <td>${item.device_category}</td>
                            <td><code>${item.serial_number || ''}</code></td>
                            <td><span class="badge ${getStatusBadgeClass(item.status)}">${item.status}</span></td>`;
                        list.appendChild(row);
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

    // Client-side filters
    document.addEventListener('DOMContentLoaded', function(){
        const catFilter = document.getElementById('filterCategories');
        const roomFilter = document.getElementById('filterRooms');
        if (catFilter) {
            catFilter.addEventListener('input', function(){
                const q = this.value.toLowerCase();
                document.querySelectorAll('.table tbody tr').forEach(function(tr){
                    if (tr.closest('.col-lg-6') || true) {}
                });
                // Filter first table only
                document.querySelectorAll('.row .col-lg-6:nth-child(1) tbody tr').forEach(function(tr){
                    const name = (tr.querySelector('.category-name-cell')?.textContent||'').toLowerCase();
                    tr.style.display = name.includes(q) ? '' : 'none';
                });
            });
        }
        if (roomFilter) {
            roomFilter.addEventListener('input', function(){
                const q = this.value.toLowerCase();
                document.querySelectorAll('.row .col-lg-6:nth-child(2) tbody tr').forEach(function(tr){
                    const name = (tr.querySelector('.room-title-cell')?.textContent||'').toLowerCase();
                    tr.style.display = name.includes(q) ? '' : 'none';
                });
            });
        }
    });
</script>
@endsection