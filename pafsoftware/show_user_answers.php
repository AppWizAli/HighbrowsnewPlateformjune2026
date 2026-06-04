<?php
// Include database connection
include 'config.php';

// Start the session
session_start();

// Define how many results per page
$results_per_page = 20;

// Determine the current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

// Calculate the starting limit number
$starting_limit = ($page - 1) * $results_per_page;

// Get the selected user's name from the query parameter (if any)
$selected_user = isset($_GET['user']) ? $_GET['user'] : '';

// Get total number of rows in the table to calculate total pages
$total_query = "SELECT COUNT(*) as total FROM answers";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $results_per_page);

// Fetch data with limit and offset for pagination
// Fetch data with limit and offset for pagination
$sql = "
    SELECT 
        u.name as user_name, 
        q.question_text, 
        q.correct_answer, 
        a.answer, 
        CASE q.correct_answer
            WHEN 'A' THEN CONCAT('Option A: ', q.option_a)
            WHEN 'B' THEN CONCAT('Option B: ', q.option_b)
            WHEN 'C' THEN CONCAT('Option C: ', q.option_c)
            WHEN 'D' THEN CONCAT('Option D: ', q.option_d)
            WHEN 'E' THEN CONCAT('Option E: ', q.option_e)
            ELSE 'Unknown'
        END as correct_option,
        CASE a.answer
            WHEN 'A' THEN CONCAT('Option A: ', q.option_a)
            WHEN 'B' THEN CONCAT('Option B: ', q.option_b)
            WHEN 'C' THEN CONCAT('Option C: ', q.option_c)
            WHEN 'D' THEN CONCAT('Option D: ', q.option_d)
            WHEN 'E' THEN CONCAT('Option E: ', q.option_e)
            ELSE 'No Answer'
        END as user_answer
    FROM answers a
    JOIN questions q ON a.question_id = q.id
    JOIN useres u ON a.user_id = u.id
";

// If a user is selected, filter by that user
if (!empty($selected_user)) {
    $sql .= " WHERE u.name = '$selected_user' ";
}

// Add ordering to the query
$sql .= " ORDER BY u.name ASC ";

// Add pagination limit
$sql .= " LIMIT $starting_limit, $results_per_page";


$result = $conn->query($sql);

// Get all unique users for the dropdown filter
$users_query = "SELECT DISTINCT name FROM useres";
$users_result = $conn->query($users_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style1.css">
</head>

<body>
    <div class="main">
        <?php include "header.php"; ?>
        <div class="main-content" id="main-content">
            <header>
                <h1>Welcome to the Admin Panel</h1>
            </header>
            <section>
                <h2>Show All Users Answers</h2>
            </section>
            <div class="main2">
                <form method="GET" action="">
            <div class="form-group">
                <label for="user">Select User</label>
                <select name="user" id="user" class="form-control" onchange="this.form.submit()">
                    <option value="">All Users</option>
                    <?php while ($user_row = $users_result->fetch_assoc()) { ?>
                        <option value="<?php echo $user_row['name']; ?>" 
                            <?php if ($user_row['name'] == $selected_user) echo 'selected'; ?>>
                            <?php echo $user_row['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </form>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>User Name</th>
                        <th>Question</th>
                        <th>Correct Answer (Option and Text)</th>
                        <th>User Answer (Option and Text)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Determine the row color based on whether the user's answer is correct
                            $rowClass = ($row['correct_answer'] == $row['answer']) ? 'table-success' : 'table-danger';
                    ?>
                        <tr class="<?php echo $rowClass; ?>">
                            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['question_text']); ?></td>
                            <td><?php echo htmlspecialchars($row['correct_option']); ?></td>
                            <td><?php echo htmlspecialchars($row['user_answer']); ?></td>
                        </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No data found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
           <nav>
                    <ul class="pagination justify-content-center">
                        <!-- First Page Button -->
                        <li class="page-item <?php if ($page == 1) echo 'disabled'; ?>">
                            <a class="page-link" href="?page=1<?php if (!empty($selected_user)) echo '&user=' . urlencode($selected_user); ?>">First</a>
                        </li>
                
                        <!-- Previous Page Button -->
                        <li class="page-item <?php if ($page == 1) echo 'disabled'; ?>">
                            <a class="page-link" href="?page=<?php echo max(1, $page - 1); ?><?php if (!empty($selected_user)) echo '&user=' . urlencode($selected_user); ?>">Previous</a>
                        </li>
                
                        <!-- Next Page Button -->
                        <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                            <a class="page-link" href="?page=<?php echo min($total_pages, $page + 1); ?><?php if (!empty($selected_user)) echo '&user=' . urlencode($selected_user); ?>">Next</a>
                        </li>
                
                        <!-- Last Page Button -->
                        <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                            <a class="page-link" href="?page=<?php echo $total_pages; ?><?php if (!empty($selected_user)) echo '&user=' . urlencode($selected_user); ?>">Last</a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Confirm before deleting a question
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this question?")) {
                window.location.href = 'delete_question.php?id=' + id;
            }
        }
    </script>
</body>

</html>

<?php
$conn->close();
?>
