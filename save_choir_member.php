<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$youth_id = $_POST['youth_id'];
$role = $_POST['role'];

// If voice rep → append voice
if ($role === "Voice Representative") {
    $voice = $_POST['voice_type'];
    $role = "Voice Representative (" . $voice . ")";
}

$stmt = $conn->prepare("INSERT INTO choir_members (youth_id, role) VALUES (?, ?)");
$stmt->bind_param("is", $youth_id, $role);

if ($stmt->execute()) {
    header("Location: view_choir_member.php?success=1");
} else {
    echo "Error saving choir member.";
}
?>
