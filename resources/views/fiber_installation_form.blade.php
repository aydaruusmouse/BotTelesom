<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Fiber Installation</title>
</head>
<body>
    <h1>New Fiber Installation</h1>
    <form method="POST" action="{{ route('new.fiber.installation') }}">
        @csrf
        <label for="callsub">Call Subscriber:</label>
        <input type="text" id="callsub" name="callsub" required><br><br>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" required><br><br>

        <label for="paymentMethod">Payment Method:</label>
        <select id="paymentMethod" name="paymentMethod" required>
            <option value="ZAAD">ZAAD</option>
            <option value="Other">Other</option>
        </select><br><br>

        <label for="contactNumber">Contact Number:</label>
        <input type="text" id="contactNumber" name="contactNumber" required><br><br>

        <label for="Address">Address:</label>
        <input type="text" id="Address" name="Address" required><br><br>

        <label for="Center">Center:</label>
        <input type="text" id="Center" name="Center" required><br><br>

        <label for="Discount">Discount:</label>
        <input type="number" id="Discount" name="Discount" required><br><br>

        <label for="Speed">Speed:</label>
        <input type="text" id="Speed" name="Speed" required><br><br>

        <label for="TranType">Transaction Type:</label>
        <input type="text" id="TranType" name="TranType" required><br><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required>Fiber Installation</textarea><br><br>

        <button type="submit">Submit Installation Request</button>
    </form>
</body>
</html>
