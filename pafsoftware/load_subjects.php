<?php
include 'config.php'; // Ensure that this file correctly initializes $conn

// Check if test_id is set and not empty
if (isset($_POST['test_id']) && !empty($_POST['test_id'])) {
    // Sanitize the input to avoid SQL injection and other issues
    $test_id = intval($_POST['test_id']);

    // Ensure that test_id is a valid integer
    if ($test_id > 0) {
        // Prepare and execute the SQL query to fetch subjects for the selected test
        $sql = "SELECT id, name FROM subjects WHERE test_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind the test_id parameter and execute the query
            $stmt->bind_param("i", $test_id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if any subjects were found
            if ($result->num_rows > 0) {
                // Fetch and display each subject as an option in the dropdown
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>';
                }
            } else {
                // If no subjects found for the test, display this message
                echo '<option value="">No subjects available for the selected test</option>';
            }

            // Close the prepared statement
            $stmt->close();
        } else {
            // Output a user-friendly error message if there was an issue with the SQL statement preparation
            error_log("SQL Error: " . $conn->error);  // Log the error for debugging
            echo '<option value="">Unable to load subjects at the moment. Please try again later.</option>';
        }
    } else {
        // Handle invalid test_id
        echo '<option value="">Invalid test selected</option>';
    }
} else {
    // If test_id is not set or empty, output this message
    echo '<option value="">No test selected</option>';
}

// Close the database connection
$conn->close();
?>
