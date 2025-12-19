<?php
session_start();
include 'db_connect.php';

// Ensure only logged-in admins can access
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Check if ID is passed
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // First, delete the associated file (if any)
    $stmt = $conn->prepare("SELECT document FROM youth_members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();

    if ($member && !empty($member['document'])) {
        $file_path = "uploads/" . $member['document'];
        if (file_exists($file_path)) {
            unlink($file_path); // delete the file from folder
        }
    }
    $stmt->close();

    // Now delete the member from database
    $stmt = $conn->prepare("DELETE FROM youth_members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // ✅ Check if table is now empty and reset ID counter
    $result = $conn->query("SELECT COUNT(*) AS total FROM youth_members");
    $row = $result->fetch_assoc();

    if ($row['total'] == 0) {
        $conn->query("ALTER TABLE youth_members AUTO_INCREMENT = 1");
    }

    // Redirect back to view members
    header("Location: view_member.php?success=Member deleted successfully");
    exit();
} else {
    header("Location: view_member.php?error=No member selected");
    exit();
}
?>

