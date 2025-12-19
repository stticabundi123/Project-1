<?php
session_start();
include 'db_connect.php';

// session + timeout
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_destroy();
    header("Location: login.php?error=Session expired. Please login again.");
    exit();
}
$_SESSION['last_activity'] = time();

// Validate POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    header("Location: view_member.php?error=Invalid request");
    exit();
}

$id = (int)$_POST['id'];
$full_name    = htmlspecialchars(trim($_POST['full_name']));
$gender       = $_POST['gender'];
$phone_number = htmlspecialchars(trim($_POST['phone_number']));
$location     = htmlspecialchars(trim($_POST['location']));
$join_date    = $_POST['join_date'];
$occupation   = htmlspecialchars(trim($_POST['occupation']));
$parent_name  = htmlspecialchars(trim($_POST['parent_name']));
$relationship = htmlspecialchars(trim($_POST['relationship']));
$parent_phone = htmlspecialchars(trim($_POST['parent_phone']));

// Fetch existing document
$stmt = $conn->prepare("SELECT document FROM youth_members WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$existing = $res->fetch_assoc();
$currentDoc = $existing['document'];
$stmt->close();

// Handle optional file upload
$newDoc = $currentDoc;
if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
    $allowed = ['pdf','jpg','jpeg','png'];
    $ext = strtolower(pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
        header("Location: edit_member.php?id={$id}&error=Invalid file type");
        exit();
    }

    $target_dir = 'uploads/';
    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

    $newName = time() . "_" . uniqid() . "." . $ext;
    $targetPath = $target_dir . $newName;
    if (!move_uploaded_file($_FILES['document']['tmp_name'], $targetPath)) {
        header("Location: edit_member.php?id={$id}&error=Upload failed");
        exit();
    }

    // delete old file
    if (!empty($currentDoc) && file_exists($target_dir . $currentDoc)) {
        @unlink($target_dir . $currentDoc);
    }

    $newDoc = $newName;
}

// Update DB
$update = $conn->prepare("UPDATE youth_members SET full_name=?, gender=?, phone_number=?, location=?, join_date=?, occupation=?, parent_name=?, relationship=?, parent_phone=?, document=? WHERE id=?");
$update->bind_param("ssssssssssi", $full_name, $gender, $phone_number, $location, $join_date, $occupation, $parent_name, $relationship, $parent_phone, $newDoc, $id);
$update->execute();

header("Location: view_member.php?success=Member updated");
exit();
?>
