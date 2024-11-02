<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Troubleshooting Request</title>
</head>
<body>
    <h1>Troubleshooting Request</h1>
    <form method="POST" action="{{ route('request.troubleshooting') }}">
        @csrf
        <label for="msisdn">MSISDN (Phone Number):</label>
        <input type="text" id="msisdn" name="msisdn" required><br><br>

        <label for="line_number">Line Number:</label>
        <input type="number" id="line_number" name="line_number" required><br><br>

        <label for="service_type">Service Type:</label>
        <select id="service_type" name="service_type" required>
            <option value="Internet">Internet</option>
            <option value="Line">Line</option>
        </select><br><br>

        <label for="problem_type">Problem Type:</label>
        <select id="problem_type" name="problem_type" required>
            <option value="DSL">DSL</option>
            <option value="Line">Line</option>
            <option value="Fiber">Fiber</option>
            <option value="P2P">P2P</option>
        </select><br><br>

        <button type="submit">Submit Troubleshooting Request</button>
    </form>
</body>
</html>
