<!-- filepath: /Users/redahaddan/Desktop/laravel-vue-2ca-assurance-crud/resources/views/emails/claim_status_updated.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Claim Status Updated</title>
</head>
<body>
    <h1>Claim Status Updated</h1>
    <p>Dear {{ $claim->user->name }},</p>
    <p>The status of your claim with subject "{{ $claim->subject }}" has been updated to "{{ $claim->status }}".</p>
    <p>Thank you,</p>
    <p>{{ config('app.name') }}</p>
</body>
</html>