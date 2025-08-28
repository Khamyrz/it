{{-- dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            padding: 20px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .topbar .datetime {
            font-weight: bold;
            font-size: 16px;
        }

        .topbar .account {
            position: relative;
            font-weight: bold;
        }

        .topbar .account button {
            background: none;
            border: none;
            color: #2c3e50;
            cursor: pointer;
            font-size: 16px;
        }

        .dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 10px;
            border-radius: 5px;
            z-index: 999;
        }

        .account:hover .dropdown {
            display: block;
        }

        .activity-panel {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .activity-box {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 0 8px rgba(0,0,0,0.05);
        }

        .chart-container {
            height: auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        canvas {
            max-width: 100%;
        }

        #contributionModal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }

        #contributionModal .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 500px;
            border-radius: 10px;
            position: relative;
        }

        #contributionModal .close {
            position: absolute;
            top: 10px;
            right: 15px;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .contribution-btn {
            padding: 10px 20px;
            font-size: 14px;
            background: #2980b9;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .contribution-btn:hover {
            background: #3498db;
        }
    </style>
</head>
<body>

<nav>
   <img src="{{ asset('images/logo.png') }}" alt="Logo" width="150">
    <h2>IT DEPARTMENT</h2>
    <ul>
    <li><a href="/dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
    <li><a href="/add-new-user"><i class="fas fa-user-plus"></i> Add New User</a></li>
    <li><a href="/manage-room"><i class="fas fa-door-open"></i> Room Management</a></li>
    <li><a href="{{ url('/categories') }}"><i class="fas fa-layer-group"></i> Categories</a></li>
    <li><a href="/maintenance"><i class="fas fa-tools"></i> Maintenance</a></li>
    <li><a href="/borrow"><i class="fas fa-handshake"></i> Borrow</a></li>
    <li><a href="/print-report"><i class="fas fa-print"></i> Print Report</a></li>
 <li><a href="/scan-barcode"><i class="fas fa-barcode"></i> Scan Barcode</a></li>

</ul>

</nav>

<div class="container">
    <div class="topbar">
        <div class="datetime" id="datetime"></div>
        <div class="account">
            <button>&#9660; {{ $user->full_name }}</button>
            <div class="dropdown">
                <a href="#">Profile</a><br>
                <a href="#">Settings</a><br>
                <a href="/logout">Logout</a>
            </div>
        </div>
    </div>

    <div class="activity-panel">
        <div class="activity-box">Users: {{ $pendingUsers->count() }}</div>
        <div class="activity-box">Categories/Rooms: {{ $roomItemCounts->count() }}</div>
        <div class="activity-box">Peripherals Devices: {{ $peripheralCount }}</div>
        <div class="activity-box">Computer Units: {{ $computerUnitCount }}</div>
        <div class="activity-box">Computers to be Repaired: {{ $unusableCount }}</div>
        <div class="activity-box">Peripheral Devices to be Repaired: {{ $unusableCount }}</div>
        <div class="activity-box">Borrowed Computer Units: {{ $borrowedComputerCount }}</div>
        <div class="activity-box">Borrowed Peripheral Devices: {{ $borrowedPeripheralCount }}</div>
        <div class="activity-box">Total Borrowed Items: {{ $borrowedCount }}</div>
    </div>

    <div class="chart-container">
        <h3>Inventory Overview</h3>
        <canvas id="deviceChart" height="120"></canvas>
    </div>

    <!-- Button to Trigger Modal -->
    <div style="margin-top: 10px;">
        <button class="contribution-btn" onclick="openModal()">📊 View Contribution Graph</button>
    </div>
</div>

<!-- Modal with Contribution Graph -->
<div id="contributionModal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3 style="text-align:center;">Peripheral vs Computer Contribution</h3>
        <canvas id="contributionChart" height="250"></canvas>
    </div>
</div>

<script>
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('datetime').innerText = now.toLocaleDateString('en-US', options);
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();

    const ctx = document.getElementById('deviceChart').getContext('2d');
    const deviceChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($itemCounts->pluck('device_category')) !!},
            datasets: [{
                label: 'Items per Category',
                data: {!! json_encode($itemCounts->pluck('total')) !!},
                backgroundColor: 'rgba(46, 204, 113, 0.7)',
                borderColor: 'rgba(46, 204, 113, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });

    function openModal() {
        document.getElementById('contributionModal').style.display = 'block';
        setTimeout(() => {
            if (!window.contributionChart) {
                renderContributionChart();
            }
        }, 200);
    }

    function closeModal() {
        document.getElementById('contributionModal').style.display = 'none';
    }

    function renderContributionChart() {
        const ctx = document.getElementById('contributionChart').getContext('2d');
        window.contributionChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Peripheral Devices', 'Computer Units'],
                datasets: [{
                    data: [{{ $borrowedPeripheralCount }}, {{ $borrowedComputerCount }}],
                    backgroundColor: ['rgba(52, 152, 219, 0.7)', 'rgba(231, 76, 60, 0.7)'],
                    borderColor: ['rgba(52, 152, 219, 1)', 'rgba(231, 76, 60, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                const value = context.raw;
                                const percent = ((value / total) * 100).toFixed(1);
                                return `${context.label}: ${value} (${percent}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    window.onclick = function(event) {
        const modal = document.getElementById('contributionModal');
        if (event.target === modal) {
            closeModal();
        }
    };
</script>

</body>
</html>
