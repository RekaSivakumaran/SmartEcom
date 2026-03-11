<!DOCTYPE html>
<html>
<body style="font-family:Arial,sans-serif; background:#f4f4f4; padding:20px;">
    <div style="max-width:500px; margin:0 auto; background:#fff; 
                border-radius:10px; padding:30px; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="color:#d33b33; text-align:center;">SmartEcom</h2>
        <h3>Password Reset Request</h3>
        <p>Hi {{ $customer->name }},</p>
        <p>Click the button below to reset your password. 
           This link expires in <strong>60 minutes</strong>.</p>
        <div style="text-align:center; margin:25px 0;">
            <a href="{{ $resetLink }}"
               style="background:#d33b33; color:#fff; padding:12px 30px;
                      border-radius:8px; text-decoration:none; font-weight:bold;">
                Reset Password
            </a>
        </div>
        <p style="color:#aaa; font-size:0.85em;">
            If you didn't request this, ignore this email.
        </p>
    </div>
</body>
</html>