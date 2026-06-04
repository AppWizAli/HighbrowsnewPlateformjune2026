<?php

include 'config.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $test_id = $_POST['test_id'];
    $test_name = $_POST['test_name'];
    $date_added = $_POST['date_added'];

    // Check if the test name already exists
    $check_sql = "SELECT * FROM tests WHERE test_name = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $test_name);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Test name already exists, show error message
        echo "Error: A test with the name '$test_name' already exists. Please choose a different name.";
    } else {
        // Test name is unique, proceed with insertion
        $sql = "INSERT INTO tests (test_id, test_name, date_added) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $test_id, $test_name, $date_added);

        if ($stmt->execute()) {
            // Redirect to a success or listing page after successful insertion
            header('Location: show_test.php');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $check_stmt->close();
    $conn->close();
}

?>
