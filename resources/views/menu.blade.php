<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USSD Menu</title>
</head>
<body>
    <h1>USSD Menu</h1>
    
    <!-- Form to input phone number and selection -->
    <form action="{{ route('ussd.initiatesession') }}" method="POST">
        @csrf
        <div>
            <label for="msisdn">Phone Number:</label>
            <input type="text" id="msisdn" name="msisdn" placeholder="Enter your phone number" required>
        </div>
        <div>
            <label for="data">Selection (Optional for First Request):</label>
            <input type="text" id="data" name="data" placeholder="Enter your selection (optional)" >
        </div>
        <button type="submit">Submit</button>
    </form>

    <!-- Display the response data (if any) -->
    @if(isset($responseData))
        <h2>Response Data</h2>
        <p><strong>Phone Number:</strong> {{ $msisdn }}</p>
        <p><strong>Response Data:</strong></p>
        <pre>{{ $responseData }}</pre>
    @endif

    <!-- If the user submits a second request -->
    <form action="{{ route('ussd.continuesession') }}" method="POST">
        @csrf
        <div>
            <label for="msisdn">Phone Number:</label>
            <input type="text" id="msisdn" name="msisdn" placeholder="Enter your phone number" required>
        </div>
        <div>
            <label for="data">Selection:</label>
            <input type="text" id="data" name="data" placeholder="Enter your selection (1, 2, etc.)" required>
        </div>
        <button type="submit">Continue</button>
    </form>

    <!-- Optionally, display JSON response if the request was made via an API (AJAX or browser with JSON response) -->
    <script>
        // If the page is loaded via AJAX or expects a JSON response, display JSON data
        const jsonResponse = @json($responseData ?? []);
        if (jsonResponse) {
            console.log(jsonResponse); // Log the JSON response
        }
    </script>
</body>
</html>