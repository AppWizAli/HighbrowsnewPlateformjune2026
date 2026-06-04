<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Test</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Enter ID and Code</h2>
        <form id="testForm" method="POST">
            <div class="form-group">
                <label for="userId">User ID</label>
                <input type="text" class="form-control" id="userId" name="userId" required>
            </div>
            <div class="form-group">
                <label for="code">Code</label>
                <input type="text" class="form-control" id="code" name="code" required>
            </div>
            <button type="submit" class="btn btn-primary">Start Test</button>
        </form>
    </div>

    <script>
        document.getElementById('testForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            fetch('test_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Process the response, e.g., display questions and timer
                console.log(data);
            });
        });
    </script>
    <script>
    var timer = 30 * 60; // 30 minutes in seconds
    var timerDisplay = document.getElementById('timer');

    function updateTimer() {
        var minutes = Math.floor(timer / 60);
        var seconds = timer % 60;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;
        timerDisplay.textContent = minutes + ':' + seconds;
        
        if (timer <= 0) {
            clearInterval(interval);
            // Submit the form or handle test end
            alert("Time's up!");
            document.getElementById('testForm').submit();
        }
        timer--;
    }

    var interval = setInterval(updateTimer, 1000);
    updateTimer(); // Initial call
</script>

</body>
</html>
