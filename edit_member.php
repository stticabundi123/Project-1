<?php
session_start();
include 'db_connect.php';

// Check admin session
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Auto logout after 30 min inactivity
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_destroy();
    header("Location: login.php?error=Session expired. Please login again.");
    exit();
}
$_SESSION['last_activity'] = time();

// Get member ID
$id = intval($_GET['id'] ?? 0);

// Fetch existing member
$stmt = $conn->prepare("SELECT * FROM youth_members WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Member not found.");
}
$member = $result->fetch_assoc();
$stmt->close();

// Handle update
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name     = trim($_POST['full_name']);
    $gender        = $_POST['gender'];
    $phone         = trim($_POST['phone_number']);
    $location      = trim($_POST['location']);
    $occupation    = trim($_POST['occupation']);
    $parent_name   = trim($_POST['parent_name']);
    $relationship  = trim($_POST['relationship']);
    $parent_phone  = trim($_POST['parent_phone']);

    // Handle File Upload
    $document = $member['document'];
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $error = "Invalid file type.";
        } else {
            $upload_dir = 'uploads/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

            $newFile = time() . "_" . uniqid() . "." . $ext;
            $path = $upload_dir . $newFile;

            if (move_uploaded_file($_FILES['document']['tmp_name'], $path)) {
                if (!empty($document) && file_exists($document)) {
                    @unlink($document);
                }
                $document = $path;
            } else {
                $error = "Failed to upload file.";
            }
        }
    }

    // Update database
    if (!$error) {
        $update = $conn->prepare("UPDATE youth_members SET 
            full_name=?, gender=?, phone_number=?, location=?, occupation=?, parent_name=?, relationship=?, parent_phone=?, document=? 
            WHERE id=?");
        $update->bind_param("sssssssssi", $full_name, $gender, $phone, $location, $occupation, $parent_name, $relationship, $parent_phone, $document, $id);

        if ($update->execute()) {
            $success = "Member updated successfully.";
        } else {
            $error = "Failed to update member.";
        }

        $update->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Member | Youth Dashboard</title>

<style>
* { box-sizing: border-box; font-family:"Segoe UI", Arial, sans-serif; margin:0; padding:0; }
body { background:#f4f6f8; display:flex; }

/* Sidebar */
.sidebar { width:230px; background:#003366; height:100vh; color:white; position:fixed; padding-top:30px; box-shadow:2px 0 6px rgba(0,0,0,0.1); }
.sidebar h2 { text-align:center; margin-bottom:30px; font-size:20px; }
.sidebar a { display:block; color:#e1e1e1; padding:14px 25px; text-decoration:none; transition:0.3s; }
.sidebar a:hover { background:#0055aa; color:white; padding-left:30px; }

/* Main content */
.main-content { margin-left:230px; width:calc(100% - 230px); padding:30px; }
header { background:#0066cc; color:white; padding:20px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.2); }
.logout { float:right; background:red; padding:8px 16px; border-radius:6px; color:white; text-decoration:none; }

/* Content */
.content { background:white; padding:25px; margin-top:30px; border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,0.1); }
form { display:grid; grid-template-columns:repeat(2, 1fr); gap:20px; }
label { font-weight:bold; margin-bottom:5px; }
input, select { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; }
button { grid-column:span 2; padding:12px; background:#0066cc; color:white; border:none; border-radius:6px; cursor:pointer; }

/* Messages */
.success { color:green; margin-bottom:15px; }
.error { color:red; margin-bottom:15px; }

/* ✅ MOBILE RESPONSIVENESS */
@media (max-width: 768px) {

    body { flex-direction: column; overflow-x: hidden; }

    .sidebar {
        width:100% !important;
        height:auto !important;
        position:relative !important;
        text-align:center;
        padding:10px 0 !important;
    }

    .sidebar a {
        padding:12px !important;
    }

    .main-content {
        margin-left:0 !important;
        width:100% !important;
        padding:15px !important;
    }

    form {
        grid-template-columns:1fr !important;
    }

    button {
        width:100% !important;
    }
}
</style>
</head>

<body>

<div class="sidebar">
<h2>🧭 Dashboard</h2>
<a href="dashboard.php">🏠 Home</a>
<a href="add_member.php">➕ Add Member</a>
<a href="view_member.php">👥 View Members</a>
<a href="logout.php">🚪 Logout</a>
</div>

<div class="main-content">

<header>
<h1>Edit Member</h1>
<a href="logout.php" class="logout">Logout</a>
</header>

<div class="content">

<?php if($success): ?>
<p class="success"><?php echo $success; ?></p>
<?php endif; ?>

<?php if($error): ?>
<p class="error"><?php echo $error; ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">

    <div>
        <label>Full Name</label>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($member['full_name']); ?>" required>
    </div>

    <div>
        <label>Gender</label>
        <select name="gender" required>
            <option value="Male" <?php if($member['gender']=='Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if($member['gender']=='Female') echo 'selected'; ?>>Female</option>
        </select>
    </div>

    <div>
        <label>Phone Number</label>
        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($member['phone_number']); ?>" required>
    </div>

    <div>
        <label>Location</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($member['location']); ?>" required>
    </div>

    <div>
        <label>Occupation</label>
        <input type="text" name="occupation" value="<?php echo htmlspecialchars($member['occupation']); ?>">
    </div>

    <div>
        <label>Parent Name</label>
        <input type="text" name="parent_name" value="<?php echo htmlspecialchars($member['parent_name']); ?>" required>
    </div>

    <div>
        <label>Relationship</label>
        <input type="text" name="relationship" value="<?php echo htmlspecialchars($member['relationship']); ?>" required>
    </div>

    <div>
        <label>Parent Phone</label>
        <input type="text" name="parent_phone" value="<?php echo htmlspecialchars($member['parent_phone']); ?>" required>
    </div>

    <div>
        <label>Current Document</label>
        <?php if (!empty($member['document']) && file_exists($member['document'])): ?>
            <a href="<?php echo $member['document']; ?>" target="_blank">View File</a>
        <?php else: ?>
            No file uploaded
        <?php endif; ?>
    </div>

    <div>
        <label>Replace Document</label>
        <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png">
    </div>

    <button type="submit">Update Member</button>

</form>

</div>
</div>

</body>
</html>
