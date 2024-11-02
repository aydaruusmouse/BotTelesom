<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Block Wrong Transaction</title>
</head>
<body>
    <h1>Block Wrong Transaction</h1>
    <form method="POST" action="{{ route('block.wrong.transaction') }}">
        @csrf
        <label for="msisdn">MSISDN:</label>
        <input type="text" id="msisdn" name="msisdn" required><br><br>

        <label for="transactionnumber">Transaction Number:</label>
        <input type="text" id="transactionnumber" name="transactionnumber" required><br><br>

        <label for="wrongnumber">Wrong Number:</label>
        <input type="text" id="wrongnumber" name="wrongnumber" required><br><br>

        <label for="currency_code">Currency Code:</label>
        <select id="currency_code" name="currency_code" required>
            <option value="Dollar">Dollar</option>
            <option value="Shilling">Shilling</option>
        </select><br><br>

        <button type="submit">Block Transaction</button>
    </form>
</body>
</html>
