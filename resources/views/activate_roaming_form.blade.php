<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activate Roaming</title>
</head>
<body>
    <h1>Activate Roaming</h1>
    <form method="POST" action="{{ route('activate.roaming') }}">
        @csrf
        <label for="callsub">Callsub:</label>
        <input type="text" id="callsub" name="Callsub" required><br><br>
        
        <label for="userid">UserId:</label>
        <input type="text" id="userid" name="UserId" required><br><br>
        
        <button type="submit">Activate Roaming</button>
    </form>
</body>
</html>
