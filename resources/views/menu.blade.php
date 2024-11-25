<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USSD Menu</title>
</head>
<body>
    <h1>USSD Menu</h1>

    <!-- Unified form for both requests -->
    <form action="{{ route('ussd.initiatesession') }}" method="POST">
        @csrf
        <div>
            <label for="msisdn">Phone Number:</label>
            <input type="text" id="msisdn" name="msisdn" placeholder="Enter your phone number" required>
        </div>
        <div>
            <label for="data">Selection:</label>
            <input type="text" id="data" name="data" placeholder="Enter your selection (1, 2, etc.)" required>
        </div>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
