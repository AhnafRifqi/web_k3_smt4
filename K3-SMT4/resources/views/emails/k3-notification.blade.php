<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $mailSubject }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #2563eb; color: white; padding: 16px 20px; border-radius: 8px 8px 0 0;">
        <h2 style="margin: 0; font-size: 18px;">SMK3 JNE — Notifikasi</h2>
    </div>
    <div style="border: 1px solid #e5e7eb; border-top: none; padding: 24px; border-radius: 0 0 8px 8px;">
        <h3 style="margin-top: 0; color: #111;">{{ $mailSubject }}</h3>
        <p>{{ $body }}</p>
        @if($link)
        <p style="margin-top: 24px;">
            <a href="{{ $link }}" style="display: inline-block; background: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold;">
                Buka Detail
            </a>
        </p>
        @endif
        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 24px 0;">
        <p style="font-size: 12px; color: #9ca3af; margin: 0;">
            Email ini dikirim otomatis oleh Sistem Manajemen K3 PT JNE.
        </p>
    </div>
</body>
</html>
