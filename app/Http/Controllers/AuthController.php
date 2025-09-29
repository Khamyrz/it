<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showRegisterForm() {
        return view('register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/',
            'password' => 'required|confirmed|min:6',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'email.regex' => 'Please use a valid Gmail address (e.g., user@gmail.com)',
            'email.required' => 'Gmail address is required',
        ]);

        // Check if email is verified
        if (!session('email_verification_verified') || session('email_verification_email') !== $request->email) {
            return back()->withErrors(['email' => 'Please verify your Gmail address first by completing the OTP verification.']);
        }

        $photoName = time() . '.' . $request->photo->extension();
        $request->photo->move(public_path('photos'), $photoName);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photo' => $photoName,
            'is_approved' => false, // default to pending
            'is_new_user' => true, // Mark as new user for data isolation
        ];
        // Ensure non-null mobile column is satisfied if present
        try {
            if (Schema::hasColumn('users', 'mobile') && !isset($data['mobile'])) {
                $data['mobile'] = '';
            }
        } catch (\Throwable $e) {}

        $user = User::create($data);

        // Clear email verification session
        session()->forget(['email_verification_otp', 'email_verification_email', 'email_verification_expires', 'email_verification_verified']);

        // Send approval notification to admin
        $this->sendAdminApprovalNotification($user);

        return redirect('/login')->with('success', 'Registration submitted successfully! Your account is pending approval from the administrator. You will receive an email notification once approved.');
    }

    public function showLoginForm() {
        return view('login');
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/',
            'password' => 'required',
        ], [
            'email.regex' => 'Please use a valid Gmail address (e.g., user@gmail.com)',
            'email.required' => 'Gmail address is required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (!Auth::user()->is_approved) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is pending approval from the administrator. Once approved by iitech.inventory@gmail.com, you can login directly to the IT Inventory System. You will receive an email notification when approved.']);
            }

            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function dashboard() {
        return view('dashboard', ['user' => Auth::user()]);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    /**
     * Serve the reference ID image (ako.png) for client-side comparison
     */
    public function idImage()
    {
        $path = base_path('ako.png');
        if (!File::exists($path)) {
            abort(404, 'ID image not found');
        }
        return response()->file($path, [
            'Content-Type' => File::mimeType($path),
            'Content-Disposition' => 'inline; filename="ako.png"'
        ]);
    }

    /**
     * Login the user when client-side scan succeeds
     */
    public function loginByScan(Request $request)
    {
        // Basic server-side guard: require a boolean flag that client only sends on exact match
        $request->validate([
            'scan_ok' => 'required|boolean'
        ]);

        if (!$request->boolean('scan_ok')) {
            return response()->json(['message' => 'Scan failed'], 422);
        }

        // Choose an approved user to authenticate the session
        $user = \App\Models\User::where('is_approved', true)->first();
        if (!$user) {
            return response()->json(['message' => 'No approved user available'], 422);
        }

        Auth::login($user);
        $request->session()->regenerate();
        return response()->json(['redirect' => url('/dashboard')]);
    }

    // Show list of users pending approval
    public function showPendingAccounts() {
        $users = User::where('is_approved', false)->get();
        return view('add-new-user', compact('users'));
    }


    // Password Reset Methods
    public function sendOTP(Request $request) {
        $request->validate([
            'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'
        ], [
            'email.regex' => 'Please use a valid Gmail address (e.g., user@gmail.com)',
            'email.required' => 'Gmail address is required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'No account found with this email'], 404);
        }

        // Lockout check
        $lockUntil = session('password_reset_lock_until');
        if ($lockUntil && now()->lt($lockUntil)) {
            return response()->json([
                'message' => 'Too many attempts. Try again later.',
                'locked_until' => $lockUntil->toIso8601String(),
            ], 429);
        }

        // Generate 6-digit OTP and store for 5 minutes
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        session([
            'password_reset_email' => $request->email,
            'password_reset_otp' => $otp,
            'password_reset_expires' => now()->addMinutes(5),
            'password_reset_attempts' => 0,
        ]);

        try {
            // Check if Gmail credentials are configured
            $gmailPassword = env('MAIL_PASSWORD');
            if (empty($gmailPassword) || $gmailPassword === 'your-app-password-here') {
                Log::error('Gmail SMTP password not configured. Please set MAIL_PASSWORD in .env file');
                
                // For development: return OTP in response when SMTP is not configured
                if (env('APP_DEBUG', false)) {
                    return response()->json([
                        'message' => 'OTP sent to your email (Development Mode - SMTP not configured)',
                        'token' => 'reset_' . sha1($request->email . '|' . time()),
                        'debug_otp' => $otp, // Only in debug mode
                        'error' => 'SMTP_NOT_CONFIGURED'
                    ]);
                }
                
                return response()->json([
                    'message' => 'Email service not configured. Please contact administrator.',
                    'error' => 'SMTP_NOT_CONFIGURED'
                ], 500);
            }

            Mail::send('emails.password_otp', ['otp' => $otp, 'user' => $user], function ($message) use ($request) {
                $message->from('iitech.inventory@gmail.com', 'IT Inventory System')
                        ->to($request->email)
                        ->subject('Your Password Reset OTP - IT Inventory System');
            });
        } catch (\Throwable $e) {
            Log::error('Password reset OTP email send failed: ' . $e->getMessage());
            Log::error('Error details: ' . $e->getTraceAsString());
            
            // Provide more specific error messages
            if (strpos($e->getMessage(), 'Authentication failed') !== false) {
                return response()->json([
                    'message' => 'Email authentication failed. Please check Gmail credentials.',
                    'error' => 'AUTH_FAILED'
                ], 500);
            } elseif (strpos($e->getMessage(), 'Connection refused') !== false) {
                return response()->json([
                    'message' => 'Cannot connect to email server. Please check network settings.',
                    'error' => 'CONNECTION_FAILED'
                ], 500);
            } else {
                return response()->json([
                    'message' => 'Failed to send OTP email: ' . $e->getMessage(),
                    'error' => 'SEND_FAILED'
                ], 500);
            }
        }

        return response()->json([
            'message' => 'OTP sent to your email',
            'token' => 'reset_' . sha1($request->email . '|' . time()),
        ]);
    }

    public function verifyOTP(Request $request) {
        $request->validate([
            'token' => 'required',
            'otp' => 'required|digits:6',
            'email' => 'required|email'
        ]);

        $storedOTP = session('password_reset_otp');
        $storedEmail = session('password_reset_email');
        $expires = session('password_reset_expires');
        $attempts = (int) session('password_reset_attempts', 0);
        if (!$storedOTP || !$expires || now()->gt($expires) || !$storedEmail || $storedEmail !== $request->email) {
            return response()->json(['message' => 'OTP expired or invalid'], 400);
        }
        if ($storedOTP !== $request->otp) {
            $attempts++;
            session(['password_reset_attempts' => $attempts]);
            if ($attempts >= 3) {
                $lockUntil = now()->addMinutes(5);
                session(['password_reset_lock_until' => $lockUntil]);
                return response()->json([
                    'message' => 'Too many invalid attempts. Try again later.',
                    'locked_until' => $lockUntil->toIso8601String(),
                ], 429);
            }
            return response()->json(['message' => 'Invalid OTP', 'remaining_attempts' => max(0, 3 - $attempts)], 400);
        }
        session(['password_reset_verified' => true, 'password_reset_attempts' => 0]);
        return response()->json(['message' => 'OTP verified successfully']);
    }

    public function updatePassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        if (!session('password_reset_verified')) {
            return response()->json(['message' => 'OTP verification required'], 400);
        }

        $email = session('password_reset_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Clear reset session
        session()->forget(['password_reset_otp', 'password_reset_email', 'password_reset_expires', 'password_reset_verified']);

        return response()->json(['message' => 'Password updated successfully']);
    }

    // Email Verification Methods for Registration
    public function sendEmailVerificationOTP(Request $request) {
        $request->validate([
            'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'
        ], [
            'email.regex' => 'Please use a valid Gmail address (e.g., user@gmail.com)',
            'email.required' => 'Gmail address is required',
        ]);

        // Check if email is already registered
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return response()->json(['message' => 'This Gmail address is already registered'], 409);
        }

        // Lockout check for email verification
        $lockUntil = session('email_verification_lock_until');
        if ($lockUntil && now()->lt($lockUntil)) {
            return response()->json([
                'message' => 'Too many attempts. Try again later.',
                'locked_until' => $lockUntil->toIso8601String(),
            ], 429);
        }

        // Generate 6-digit OTP and store for 5 minutes
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        session([
            'email_verification_email' => $request->email,
            'email_verification_otp' => $otp,
            'email_verification_expires' => now()->addMinutes(5),
            'email_verification_attempts' => 0,
        ]);

        try {
            // Check if Gmail credentials are configured
            $gmailPassword = env('MAIL_PASSWORD');
            if (empty($gmailPassword) || $gmailPassword === 'your-app-password-here') {
                Log::error('Gmail SMTP password not configured. Please set MAIL_PASSWORD in .env file');
                
                // For development: return OTP in response when SMTP is not configured
                if (env('APP_DEBUG', false)) {
                    return response()->json([
                        'message' => 'Email verification OTP sent (Development Mode - SMTP not configured)',
                        'token' => 'email_verify_' . sha1($request->email . '|' . time()),
                        'debug_otp' => $otp, // Only in debug mode
                        'error' => 'SMTP_NOT_CONFIGURED'
                    ]);
                }
                
                return response()->json([
                    'message' => 'Email service not configured. Please contact administrator.',
                    'error' => 'SMTP_NOT_CONFIGURED'
                ], 500);
            }

            Mail::send('emails.email_verification', ['otp' => $otp, 'email' => $request->email], function ($message) use ($request) {
                $message->from('iitech.inventory@gmail.com', 'IT Inventory System')
                        ->to($request->email)
                        ->subject('Verify Your Gmail - IT Inventory System Registration');
            });
        } catch (\Throwable $e) {
            Log::error('Email verification OTP send failed: ' . $e->getMessage());
            Log::error('Error details: ' . $e->getTraceAsString());
            
            // Provide more specific error messages
            if (strpos($e->getMessage(), 'Authentication failed') !== false) {
                return response()->json([
                    'message' => 'Email authentication failed. Please check Gmail credentials.',
                    'error' => 'AUTH_FAILED'
                ], 500);
            } elseif (strpos($e->getMessage(), 'Connection refused') !== false) {
                return response()->json([
                    'message' => 'Cannot connect to email server. Please check network settings.',
                    'error' => 'CONNECTION_FAILED'
                ], 500);
            } else {
                return response()->json([
                    'message' => 'Failed to send verification OTP: ' . $e->getMessage(),
                    'error' => 'SEND_FAILED'
                ], 500);
            }
        }

        return response()->json([
            'message' => 'Verification OTP sent to your Gmail',
            'token' => 'email_verify_' . sha1($request->email . '|' . time()),
        ]);
    }

    public function verifyEmailOTP(Request $request) {
        $request->validate([
            'token' => 'required',
            'otp' => 'required|digits:6',
            'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'
        ], [
            'email.regex' => 'Please use a valid Gmail address (e.g., user@gmail.com)',
        ]);

        $storedOTP = session('email_verification_otp');
        $storedEmail = session('email_verification_email');
        $expires = session('email_verification_expires');
        $attempts = (int) session('email_verification_attempts', 0);

        if (!$storedOTP || !$expires || now()->gt($expires) || !$storedEmail || $storedEmail !== $request->email) {
            return response()->json(['message' => 'OTP expired or invalid'], 400);
        }

        if ($storedOTP !== $request->otp) {
            $attempts++;
            session(['email_verification_attempts' => $attempts]);
            if ($attempts >= 3) {
                $lockUntil = now()->addMinutes(5);
                session(['email_verification_lock_until' => $lockUntil]);
                return response()->json([
                    'message' => 'Too many invalid attempts. Try again later.',
                    'locked_until' => $lockUntil->toIso8601String(),
                ], 429);
            }
            return response()->json(['message' => 'Invalid OTP', 'remaining_attempts' => max(0, 3 - $attempts)], 400);
        }

        // Mark email as verified
        session(['email_verification_verified' => true, 'email_verification_attempts' => 0]);
        return response()->json(['message' => 'Email verified successfully']);
    }

    // Admin Approval System
    private function sendAdminApprovalNotification($user) {
        try {
            $approvalUrl = url('/add-new-user');
            
            // Generate secure approval and rejection links
            $approveToken = hash('sha256', $user->email . '|' . $user->created_at->timestamp . '|approve');
            $rejectToken = hash('sha256', $user->email . '|' . $user->created_at->timestamp . '|reject');
            
            $approveUrl = url('/email-approve/' . $user->id . '/' . $approveToken);
            $rejectUrl = url('/email-reject/' . $user->id . '/' . $rejectToken);
            
            // Check if Gmail credentials are configured
            $gmailPassword = env('MAIL_PASSWORD');
            if (empty($gmailPassword) || $gmailPassword === 'your-app-password-here') {
                Log::warning('Gmail SMTP not configured. Admin approval notification not sent for user: ' . $user->email);
                return;
            }
            
            Mail::send('emails.admin_approval_request', [
                'user' => $user,
                'approvalUrl' => $approvalUrl,
                'approveUrl' => $approveUrl,
                'rejectUrl' => $rejectUrl
            ], function ($message) use ($user) {
                $message->from('iitech.inventory@gmail.com', 'IT Inventory System')
                        ->to('iitech.inventory@gmail.com')
                        ->subject('New Account Registration - Approval Required: ' . $user->name);
            });
            
            Log::info('Admin approval notification sent successfully for user: ' . $user->email);
        } catch (\Throwable $e) {
            Log::error('Failed to send admin approval notification for user ' . $user->email . ': ' . $e->getMessage());
            Log::error('Error details: ' . $e->getTraceAsString());
            // Don't fail registration if email fails, but log the issue
        }
    }

    public function approveUser($id) {
        try {
            $user = User::findOrFail($id);
            
            // Check if user is already approved
            if ($user->is_approved) {
                return redirect()->back()->with('warning', 'User is already approved.');
            }
            
            $user->is_approved = true;
            $user->save();

            Log::info('User approved successfully: ' . $user->email . ' (ID: ' . $user->id . ')');

            // Send approval confirmation to user
            $this->sendUserApprovalConfirmation($user);

            return redirect()->back()->with('success', 'User approved successfully! ' . $user->name . ' can now login directly to the IT Inventory System. Confirmation email sent to ' . $user->email);
        } catch (\Throwable $e) {
            Log::error('Failed to approve user ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to approve user. Please try again.');
        }
    }

    public function rejectUser($id) {
        try {
            $user = User::findOrFail($id);
            $userEmail = $user->email;
            $userName = $user->name;
            
            $user->delete();

            Log::info('User rejected and deleted: ' . $userEmail . ' (ID: ' . $id . ')');

            // Send rejection notification to user
            $this->sendUserRejectionNotification($userEmail);

            return redirect()->back()->with('success', 'User ' . $userName . ' rejected and deleted. Rejection email sent to ' . $userEmail);
        } catch (\Throwable $e) {
            Log::error('Failed to reject user ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to reject user. Please try again.');
        }
    }

    private function sendUserApprovalConfirmation($user) {
        try {
            Mail::send('emails.user_approval_confirmation', [
                'user' => $user
            ], function ($message) use ($user) {
                $message->from('iitech.inventory@gmail.com', 'IT Inventory System')
                        ->to($user->email)
                        ->subject('Account Approved - IT Inventory System Access Granted');
            });
            
            Log::info('User approval confirmation sent to: ' . $user->email);
        } catch (\Throwable $e) {
            Log::error('Failed to send user approval confirmation: ' . $e->getMessage());
        }
    }

    private function sendUserRejectionNotification($userEmail) {
        try {
            Mail::send('emails.user_rejection_notification', [
                'userEmail' => $userEmail
            ], function ($message) use ($userEmail) {
                $message->from('iitech.inventory@gmail.com', 'IT Inventory System')
                        ->to($userEmail)
                        ->subject('Account Registration Rejected - IT Inventory System');
            });
            
            Log::info('User rejection notification sent to: ' . $userEmail);
        } catch (\Throwable $e) {
            Log::error('Failed to send user rejection notification: ' . $e->getMessage());
        }
    }

    // Test method to check email functionality
    public function testEmailSystem() {
        try {
            // Test sending email to admin
            $testUser = (object) [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'created_at' => now()
            ];
            
            $approvalUrl = url('/add-new-user');
            
            Mail::send('emails.admin_approval_request', [
                'user' => $testUser,
                'approvalUrl' => $approvalUrl
            ], function ($message) {
                $message->from('iitech.inventory@gmail.com', 'IT Inventory System')
                        ->to('iitech.inventory@gmail.com')
                        ->subject('TEST: Email System Check - IT Inventory System');
            });
            
            return response()->json(['message' => 'Test email sent successfully to iitech.inventory@gmail.com']);
        } catch (\Throwable $e) {
            Log::error('Email test failed: ' . $e->getMessage());
            return response()->json(['error' => 'Email test failed: ' . $e->getMessage()], 500);
        }
    }

    // Method to resend admin notification for a specific user
    public function resendAdminNotification($id) {
        try {
            $user = User::findOrFail($id);
            $this->sendAdminApprovalNotification($user);
            return redirect()->back()->with('success', 'Admin notification resent for ' . $user->name);
        } catch (\Throwable $e) {
            Log::error('Failed to resend admin notification for user ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to resend notification. Please try again.');
        }
    }

    // Email-based approval system
    public function emailApproveUser($id, $token) {
        try {
            $user = User::findOrFail($id);
            
            // Verify the token matches the user's email and current timestamp
            $expectedToken = hash('sha256', $user->email . '|' . $user->created_at->timestamp . '|approve');
            if (!hash_equals($expectedToken, $token)) {
                return view('email-approval-result', [
                    'success' => false,
                    'message' => 'Invalid approval link. This link may have expired or been tampered with.',
                    'user' => $user
                ]);
            }
            
            // Check if user is already approved
            if ($user->is_approved) {
                return view('email-approval-result', [
                    'success' => true,
                    'message' => 'Account is already approved. User can login to the IT Inventory System.',
                    'user' => $user,
                    'alreadyApproved' => true
                ]);
            }
            
            // Approve the user
            $user->is_approved = true;
            $user->save();

            Log::info('User approved via email: ' . $user->email . ' (ID: ' . $user->id . ')');

            // Send approval confirmation to user
            $this->sendUserApprovalConfirmation($user);

            return view('email-approval-result', [
                'success' => true,
                'message' => 'Account approved successfully! ' . $user->name . ' can now login to the IT Inventory System.',
                'user' => $user,
                'alreadyApproved' => false
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to approve user via email ID ' . $id . ': ' . $e->getMessage());
            return view('email-approval-result', [
                'success' => false,
                'message' => 'Failed to approve account. Please try again or contact the administrator.',
                'user' => null
            ]);
        }
    }

    public function emailRejectUser($id, $token) {
        try {
            $user = User::findOrFail($id);
            
            // Verify the token matches the user's email and current timestamp
            $expectedToken = hash('sha256', $user->email . '|' . $user->created_at->timestamp . '|reject');
            if (!hash_equals($expectedToken, $token)) {
                return view('email-approval-result', [
                    'success' => false,
                    'message' => 'Invalid rejection link. This link may have expired or been tampered with.',
                    'user' => $user
                ]);
            }
            
            $userEmail = $user->email;
            $userName = $user->name;
            
            // Delete the user
            $user->delete();

            Log::info('User rejected via email: ' . $userEmail . ' (ID: ' . $id . ')');

            // Send rejection notification to user
            $this->sendUserRejectionNotification($userEmail);

            return view('email-approval-result', [
                'success' => true,
                'message' => 'Account rejected and deleted. Rejection notification sent to ' . $userEmail,
                'user' => null,
                'rejected' => true
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to reject user via email ID ' . $id . ': ' . $e->getMessage());
            return view('email-approval-result', [
                'success' => false,
                'message' => 'Failed to reject account. Please try again or contact the administrator.',
                'user' => null
            ]);
        }
    }
}
    