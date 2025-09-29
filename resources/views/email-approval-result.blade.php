<!DOCTYPE html>
<html>
<head>
    <title>Account Approval Result</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .result-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .result-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .success-icon {
            color: #28a745;
        }

        .error-icon {
            color: #dc3545;
        }

        .result-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .success-title {
            color: #28a745;
        }

        .error-title {
            color: #dc3545;
        }

        .result-message {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .user-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }

        .user-details h3 {
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

        .user-details p {
            margin: 8px 0;
            color: #666;
        }

        .action-buttons {
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            margin: 0 10px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 123, 255, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .security-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
            font-size: 14px;
        }

        .login-info {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            color: #155724;
        }

        @media (max-width: 768px) {
            .result-container {
                padding: 30px 20px;
            }
            
            .btn {
                display: block;
                margin: 10px 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="result-container">
        @if($success)
            <div class="result-icon success-icon">✅</div>
            <h1 class="result-title success-title">Account {{ isset($rejected) ? 'Rejected' : 'Approved' }} Successfully!</h1>
        @else
            <div class="result-icon error-icon">❌</div>
            <h1 class="result-title error-title">Action Failed</h1>
        @endif

        <p class="result-message">{{ $message }}</p>

        @if($user && !isset($rejected))
            <div class="user-details">
                <h3>Account Details</h3>
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Status:</strong> 
                    @if(isset($alreadyApproved) && $alreadyApproved)
                        <span style="color: #28a745; font-weight: bold;">Already Approved</span>
                    @else
                        <span style="color: #28a745; font-weight: bold;">Approved</span>
                    @endif
                </p>
            </div>

            @if(!isset($alreadyApproved))
                <div class="login-info">
                    <h4 style="margin-bottom: 10px;">🚀 User Can Now Login!</h4>
                    <p style="margin: 5px 0;">The user can now login directly to the IT Inventory System using their Gmail account.</p>
                    <p style="margin: 5px 0;">An approval confirmation email has been sent to {{ $user->email }}</p>
                </div>
            @endif
        @endif

        @if(isset($rejected))
            <div class="security-notice">
                <h4 style="margin-bottom: 10px;">⚠️ Account Deleted</h4>
                <p style="margin: 5px 0;">The user account has been permanently deleted from the system.</p>
                <p style="margin: 5px 0;">A rejection notification has been sent to the user's email address.</p>
            </div>
        @endif

        <div class="action-buttons">
            @if($success && !isset($rejected))
                <a href="{{ url('/login') }}" class="btn btn-primary">Go to Login Page</a>
            @endif
            <a href="{{ url('/add-new-user') }}" class="btn btn-secondary">View Admin Panel</a>
        </div>

        <div class="security-notice">
            <h4 style="margin-bottom: 10px;">🔐 Security Information</h4>
            <p style="margin: 5px 0;">This action was performed via secure email link.</p>
            <p style="margin: 5px 0;">All actions are logged for security and audit purposes.</p>
        </div>
    </div>
</body>
</html>















