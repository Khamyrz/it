<div style="font-family:Arial,Helvetica,sans-serif; font-size:14px; color:#222;">
    <p>Hi {{ isset($user) ? $user->name : 'there' }},</p>
    <p>Your login One-Time Password (OTP) for your IT Inventory System account is:</p>
    <p style="font-size:24px; font-weight:bold; letter-spacing:3px; color:#007bff;">{{ $otp }}</p>
    <p>This code is valid for 5 minutes. Enter this code in the login OTP modal to complete your login.</p>
    <p>If you did not attempt to login, please ignore this email and consider changing your password.</p>
    <p>Thanks,<br/>IT Inventory System Team<br/>iitech.inventory@gmail.com</p>
</div>

