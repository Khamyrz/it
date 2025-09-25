@extends('layouts.app')

@section('title', 'Pending User Approvals')

@section('content')
    <style>
        .main-content {
            padding: 40px 60px;
            background: #f8f9fa;
            min-height: 100vh;
            margin-left: auto;
            margin-right: auto;
            max-width: 1200px;
        }
        
        .page-header {
            margin-bottom: 40px;
            text-align: center;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
        }
        
        .page-title::before {
            content: "👥";
            font-size: 24px;
        }
        
        .success-alert {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            border: none;
            border-left: 4px solid #28a745;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 25px;
            color: #155724;
            font-weight: 500;
            box-shadow: 0 2px 10px rgba(40, 167, 69, 0.1);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.6;
        }
        
        .empty-state-text {
            font-size: 18px;
            color: #6c757d;
            margin: 0;
        }
        
        .approvals-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: none;
            margin: 0 auto;
            width: 100%;
        }
        
        .table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .table-header th {
            padding: 20px 24px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }
        
        .table-body td {
            padding: 20px 24px;
            border: none;
            border-bottom: 1px solid #f1f3f4;
            vertical-align: middle;
        }
        
        .table-body tr:last-child td {
            border-bottom: none;
        }
        
        .table-body tr:hover {
            background: #f8f9fa;
            transition: background 0.3s ease;
        }
        
        .user-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e9ecef;
            transition: transform 0.3s ease;
        }
        
        .user-photo:hover {
            transform: scale(1.1);
        }
        
        .user-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 16px;
        }
        
        .user-email {
            color: #6c757d;
            font-size: 14px;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        
        .btn-approve {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .btn-approve:hover {
            background: linear-gradient(135deg, #218838, #1aa085);
        }
        
        .btn-reject {
            background: linear-gradient(135deg, #dc3545, #e83e8c);
            color: white;
        }
        
        .btn-reject:hover {
            background: linear-gradient(135deg, #c82333, #d91a72);
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin: 0 auto 30px auto;
            max-width: 300px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            text-align: center;
        }
        
        .stats-number {
            font-size: 32px;
            font-weight: 700;
            color: #667eea;
            margin: 0;
        }
        
        .stats-label {
            color: #6c757d;
            font-size: 14px;
            margin: 5px 0 0 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 20px 30px;
                margin: 0;
                max-width: 100%;
            }
            
            .approvals-table {
                overflow-x: auto;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 8px;
            }
            
            .btn {
                padding: 8px 16px;
                font-size: 12px;
            }
            
            .user-photo {
                width: 45px;
                height: 45px;
            }
        }
    </style>

    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">Pending Account Approvals</h1>
            <div style="margin-top: 15px;">
                
            </div>
        </div>

        @if(session('success'))
            <div class="success-alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px 20px; margin-bottom: 25px; border-radius: 8px; font-weight: 500;">
                {{ session('warning') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px 20px; margin-bottom: 25px; border-radius: 8px; font-weight: 500;">
                {{ session('error') }}
            </div>
        @endif

        @if($users->isNotEmpty())
            <div class="stats-card">
                <h2 class="stats-number">{{ $users->count() }}</h2>
                <p class="stats-label">Pending Approvals</p>
            </div>
        @endif

        @if($users->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">✅</div>
                <p class="empty-state-text">No pending user accounts to review</p>
            </div>
        @else
            <table class="approvals-table">
                <thead class="table-header">
                    <tr>
                        <th>Photo</th>
                        <th>Full Name</th>
                        <th>Email Address</th>
                        <th>Registration Date</th>
                        <th>Account Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-body">
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <img src="{{ asset('photos/' . $user->photo) }}" 
                                     alt="{{ $user->name }}" 
                                     class="user-photo">
                            </td>
                            <td>
                                <div class="user-name">{{ $user->name }}</div>
                            </td>
                            <td>
                                <div class="user-email">{{ $user->email }}</div>
                            </td>
                            <td>
                                <div style="color: #6c757d; font-size: 14px;">
                                    {{ $user->created_at->format('M d, Y') }}<br>
                                    <small>{{ $user->created_at->format('g:i A') }}</small>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 5px;">
                                    <span style="background: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; text-align: center;">
                                        ⏳ Pending Approval
                                    </span>
                                    <span style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; text-align: center;">
                                        ✅ Gmail Verified
                                    </span>
                                    <span style="background: #e3f2fd; color: #1976d2; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; text-align: center;">
                                        🚀 Ready for Direct Access
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <form method="POST" action="/approve-user/{{ $user->id }}" style="display: inline;" onsubmit="return confirmApproval('{{ $user->name }}', '{{ $user->email }}')">
                                        @csrf
                                        <button class="btn btn-approve" type="submit">
                                            ✓ Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="/reject-user/{{ $user->id }}" style="display: inline;" onsubmit="return confirmRejection('{{ $user->name }}', '{{ $user->email }}')">
                                        @csrf
                                        <button class="btn btn-reject" type="submit">
                                            ✗ Reject
                                        </button>
                                    </form>
                                    <form method="POST" action="/resend-admin-notification/{{ $user->id }}" style="display: inline;">
                                        @csrf
                                        <button class="btn" type="submit" style="background: #17a2b8; color: white; font-size: 11px; padding: 8px 12px;">
                                            📧 Resend
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <script>
        function confirmApproval(name, email) {
            return confirm(`Are you sure you want to APPROVE this account?\n\nUser: ${name}\nEmail: ${email}\n\nThis will:\n✅ Grant DIRECT LOGIN access to the IT Inventory System\n✅ Send approval confirmation email to user\n✅ User can login immediately with their Gmail account\n✅ No need to login to infotech-inventory.com first\n\nClick OK to approve or Cancel to abort.`);
        }

        function confirmRejection(name, email) {
            return confirm(`Are you sure you want to REJECT this account?\n\nUser: ${name}\nEmail: ${email}\n\nThis will:\n❌ Permanently delete the user account\n❌ Send rejection notification email to user\n❌ User will not be able to register again with this email\n\nClick OK to reject or Cancel to abort.`);
        }
    </script>
@endsection