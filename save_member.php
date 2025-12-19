<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Auto logout after 30 minutes of inactivity
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_destroy();
    header("Location: login.php?error=Session expired. Please login again.");
    exit();
}

// Update last activity timestamp
$_SESSION['last_activity'] = time();



include 'db_connect.php';

// Get form data safely
$full_name     = htmlspecialchars($_POST['full_name']);
$gender        = $_POST['gender'];
$phone_number  = htmlspecialchars($_POST['phone_number']);
$location      = htmlspecialchars($_POST['location']);
$birth_date     = $_POST['birth_date'];
$occupation    = htmlspecialchars($_POST['occupation']);
$parent_name   = htmlspecialchars($_POST['parent_name']);
$relationship  = htmlspecialchars($_POST['relationship']);
$parent_phone  = htmlspecialchars($_POST['parent_phone']);

// File upload handling
$target_dir = "uploads/";
if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

$document = "";
if (isset($_FILES["document"]) && $_FILES["document"]["error"] == 0) {
    $file_ext = strtolower(pathinfo($_FILES["document"]["name"], PATHINFO_EXTENSION));
    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];

    if (!in_array($file_ext, $allowed)) {
        die("Invalid file type.");
    }

    $new_name = time() . "_" . uniqid() . "." . $file_ext;
    $target_file = $target_dir . $new_name;

    if (!move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
        die("Error uploading file.");
    }

    $document = $target_file;
}

// Insert into database using prepared statement
$stmt = $conn->prepare("INSERT INTO youth_members (full_name, gender, phone_number, location, birth_date, occupation, parent_name, relationship, parent_phone, document) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssss", $full_name, $gender, $phone_number, $location, $birth_date, $occupation, $parent_name, $relationship, $parent_phone, $document);

if ($stmt->execute()) {
    header("Location: view_member.php?success=Member added successfully");
    exit();
} else {
    die("Error saving member: " . $conn->error);
}

$stmt->close();
$conn->close();
?>


