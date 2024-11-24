<!-- resources/views/otp_form.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send OTP</title>
</head>
<body>
    <h1>Send OTP</h1>
    <form action="{{ route('send_otp') }}" method="POST">
        @csrf
        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" id="phone_number" required>
        <button type="submit">Send OTP</button>
    </form>

    @if (session('status'))
        <p>{{ session('status') }}</p>
        <p>OTP: {{ session('otp') }}</p>
    @endif
</body>
</html>
