<!-- resources/views/sms_form.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send SMS</title>
</head>
<body>
    <h1>Send Advertisement SMS</h1>
    <form action="{{ route('send.advertisement.sms') }}" method="POST">
        @csrf
        <label for="msisdn">MSISDN:</label>
        <input type="text" id="msisdn" name="msisdn" required>
        <br>
        <label for="status">Status:</label>
        <input type="text" id="status" name="status" required>
        <br>
        <button type="submit">Send SMS</button>
    </form>
</body>
</html>
