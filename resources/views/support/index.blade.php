<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Customer References</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Customer Support - Get References</h2>

        <!-- Display Status or Error Message -->
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @elseif(session('references'))
            <div class="alert alert-success">References fetched successfully!</div>
        @endif

        <!-- Problem Type Form -->
        <form action="{{ route('support.getReferences') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="problemType" class="form-label">Select Problem Type:</label>
                <select class="form-select" id="problemType" name="problem_type" required>
                    <option value="Internet">Internet</option>
                    <option value="Line">Line</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="callsub" class="form-label">Phone Number:</label>
                <input type="text" class="form-control" id="callsub" name="callsub" placeholder="Enter phone number" required>
            </div>
            <button type="submit" class="btn btn-primary">Get References</button>
        </form>

        <!-- Display References -->
        @if(session('references'))
            <div class="mt-4">
                <h3>Available References</h3>
                <ul class="list-group">
                    @foreach(session('references') as $reference)
                        <li class="list-group-item">
                            {{ $reference['Name'] }} ({{ $reference['ServiceType'] }}) - Customer No: {{ $reference['CustomerNo'] }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
