<?php
session_start();

// 🔐 Only allow logged-in users
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../LoginReg/login.php');
    exit();
}

include '../Database/config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $adminId = intval($_GET['id']);

    // Optional: Prevent current logged-in admin from deleting themselves
    // if ($_SESSION['admin_id'] == $adminId) {
    //     header('Location: ../LoginReg/Showadmin.php?error=Cannot delete yourself.');
    //     exit();
    // }

    // Prepare statement
    $stmt = $conn->prepare("DELETE FROM admin WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $adminId);
        if ($stmt->execute()) {
            header("Location: ../LoginReg/Showadmin.php?success=Admin deleted successfully.");
        } else {
            header("Location: ../LoginReg/Showadmin.php?error=Failed to delete admin.");
        }
        $stmt->close();
    } else {
        header("Location: ../LoginReg/Showadmin.php?error=Database error.");
    }

    $conn->close();
} else {
    header("Location: ../LoginReg/Showadmin.php?error=Invalid request.");
    exit();
}
?>
<img src="../LoginReg/Showadmin.php" alt="">