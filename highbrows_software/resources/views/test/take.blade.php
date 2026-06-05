<!DOCTYPE html>
<html>
<head>
    <title>Test - Grade {{ $grade }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
<div class="container">
    <h2 class="mb-4">Test: {{ ucfirst($subject) }} (Grade {{ $grade }})</h2>

    <form method="POST" action="{{ route('tests.submit') }}">
        @csrf

        @foreach($questions as $index => $q)
            <div class="mb-3">
                <strong>Q{{ $index + 1 }}: {{ $q['question'] }}</strong><br>
                @foreach($q['options'] as $key => $option)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answers[{{ $index }}]" id="q{{ $index }}{{ $key }}" value="{{ $key }}" required>
                        <label class="form-check-label" for="q{{ $index }}{{ $key }}">
                            {{ $key }}. {{ $option }}
                        </label>
                    </div>
                @endforeach
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Submit Test</button>
    </form>
</div>
</body>
</html>
