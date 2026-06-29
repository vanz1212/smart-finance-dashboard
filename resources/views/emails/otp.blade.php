<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Smart Finance</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        .header {
            background-color: #141c2a;
            color: #ffffff;
            padding: 30px 40px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .content {
            padding: 40px;
            text-align: center;
        }
        .content p {
            font-size: 16px;
            color: #475569;
            margin-bottom: 30px;
        }
        .otp-box {
            background-color: #f1f5f9;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 20px;
            margin: 0 auto 30px;
            display: inline-block;
        }
        .otp-code {
            font-size: 36px;
            font-weight: 900;
            color: #4f46e5;
            letter-spacing: 6px;
            margin: 0;
        }
        .warning {
            font-size: 14px;
            color: #ef4444;
            font-weight: 600;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 13px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Smart Finance</h1>
        </div>
        <div class="content">
            @if ($type === 'reset')
                <h2>Permintaan Reset Password</h2>
                <p>Kami menerima permintaan untuk mereset password akun Smart Finance Anda. Berikut adalah kode OTP untuk melanjutkan proses tersebut:</p>
            @else
                <h2>Verifikasi Email Anda</h2>
                <p>Terima kasih telah bergabung dengan Smart Finance. Silakan gunakan kode OTP berikut untuk memverifikasi alamat email Anda:</p>
            @endif
            
            <div class="otp-box">
                <p class="otp-code">{{ $otpCode }}</p>
            </div>
            
            <p class="warning">Kode ini akan kedaluwarsa dalam 15 menit. Jangan bagikan kode ini kepada siapa pun.</p>
            
            @if ($type === 'reset')
                <p style="font-size: 14px; color: #64748b; margin-top: 20px;">Jika Anda tidak merasa meminta reset password, silakan abaikan email ini atau hubungi tim dukungan kami jika ada aktivitas mencurigakan.</p>
            @endif
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Nexio Dashboard. Semua Hak Dilindungi.</p>
        </div>
    </div>
</body>
</html>
