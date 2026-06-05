<!DOCTYPE html>
<html>
<head>
    <title>Test Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
<div class="container text-center">
    <h2 class="mb-4">Test Result</h2>

    <div class="card p-4 shadow-sm mx-auto" style="max-width: 500px;">
        <h4 class="mb-3">Score Summary</h4>
        <p><strong>Correct Answers:</strong> {{ $correct }} / {{ $total }}</p>
        <p><strong>Percentage:</strong> {{ number_format($percentage, 2) }}%</p>

        <h3 class="mt-4 {{ $result == 'Pass' ? 'text-success' : 'text-danger' }}">
            {{ $result }}
        </h3>
    </div>

    <a href="{{ route('home') }}" class="btn btn-outline-primary mt-4">Back to Dashboard</a>
</div>
</body>
</html>
