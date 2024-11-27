<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exchange Rate</title>
</head>
<body>
    <h1>Exchange Rate</h1>

    <!-- Check for any error messages -->
    @if(isset($error))
        <div style="color: red;">
            <h3>Error: {{ $error }}</h3>
        </div>
    @elseif(isset($data))
        <!-- Display the exchange rate data -->
        <table border="1">
            <thead>
                <tr>
                    <th>Dollar</th>
                    <th>Exchange Rate</th>
                    <th>SL Shilling</th>
                    <th>Modified Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item['Dollar'] }}</td>
                        <td>{{ $item['ExchangeRate'] }}</td>
                        <td>{{ $item['SL_Shilling'] }}</td>
                        <td>{{ $item['ModifiedDate'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
