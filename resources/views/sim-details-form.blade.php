<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIN/PUK Number Entry</title>
</head>
<body>
    <h1>Enter Phone Number</h1>
    <form action="{{ route('sim.details.submit') }}" method="POST">
        @csrf
        <label for="phoneNumber">Phone Number (9 digits):</label>
        <input type="text" id="phoneNumber" name="phoneNumber" required pattern="\d{9}">
        <button type="submit">Submit</button>
    </form>
    

    @if (session('response'))
        <h2>Response:</h2>
        <pre>{{ session('response') }}</pre>
    @endif
    
</body>
</html>
