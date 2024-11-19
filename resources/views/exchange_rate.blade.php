<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exchange Rate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Exchange Rate</h1>

    @if(isset($error))
        <p style="color: red;">{{ $error }}</p>
    @elseif(isset($data) && count($data) > 0)
        <table>
            <thead>
                <tr>
                    <th>Dollar</th>
                    <th>Exchange Rate</th>
                    <th>SL Shilling</th>
                    <th>Modified Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $rate)
                    <tr>
                        <td>${{ number_format($rate['Dollar'], 2) }}</td>
                        <td>{{ number_format($rate['ExchangeRate'], 2) }}</td>
                        <td>{{ number_format($rate['SL_Shilling'], 2) }}</td>
                        <td>{{ date('d-M-Y', strtotime($rate['ModifiedDate'])) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No exchange rate data available.</p>
    @endif
</body>
</html>
