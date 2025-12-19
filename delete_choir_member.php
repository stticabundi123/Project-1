<?php
session_start();
include 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    // Remove member only from choir table
    $stmt = $conn->prepare("DELETE FROM choir_members WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: view_choir_member.php?success=Choir member removed successfully");
        exit();
    } else {
        echo "Error deleting choir member.";
    }

    $stmt->close();
}

$conn->close();
?>
