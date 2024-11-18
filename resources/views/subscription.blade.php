<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription</title>
</head>
<body>
    <h1>Subscription Management</h1>

    <!-- Check Subscription Form -->
    <form method="POST" action="{{ route('check.subscription') }}">
        @csrf
        <h2>Check Subscription</h2>
        <label for="msisdn">MSISDN:</label>
        <input type="text" id="msisdn" name="msisdn" required>
        <label for="offer">Offer:</label>
        <input type="text" id="offer" name="offer" required>
        <button type="submit">Check</button>
    </form>

    <!-- Subscribe Form -->
    <form method="POST" action="{{ route('subscribe') }}">
        @csrf
        <h2>Subscribe</h2>
        <label for="msisdn_subscribe">MSISDN:</label>
        <input type="text" id="msisdn_subscribe" name="msisdn" required>
        <label for="offer_subscribe">Offer:</label>
        <input type="text" id="offer_subscribe" name="offer" required>
        <button type="submit">Subscribe</button>
    </form>

    <!-- Unsubscribe Form -->
    <form method="POST" action="{{ route('unsubscribe') }}">
        @csrf
        <h2>Unsubscribe</h2>
        <label for="msisdn_unsubscribe">MSISDN:</label>
        <input type="text" id="msisdn_unsubscribe" name="msisdn" required>
        <label for="offer_unsubscribe">Offer:</label>
        <input type="text" id="offer_unsubscribe" name="offer" required>
        <button type="submit">Unsubscribe</button>
    </form>

    <!-- Display API Response -->
    @if(isset($response))
        <h3>API Response:</h3>
        <pre>{{ json_encode($response, JSON_PRETTY_PRINT) }}</pre>
    @endif
</body>
</html>
