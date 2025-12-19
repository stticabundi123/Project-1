<?php
session_start();
include 'db_connect.php';

// Check admin login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Auto session timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_destroy();
    header("Location: login.php?error=Session expired. Please login again.");
    exit();
}
$_SESSION['last_activity'] = time();

// Search
$search = $_GET['search'] ?? "";
$searchTerm = "%$search%";

if ($search != "") {
    $stmt = $conn->prepare("SELECT * FROM youth_members 
                            WHERE full_name LIKE ? OR phone_number LIKE ? OR location LIKE ?
                            OR parent_name LIKE ? ORDER BY id DESC");
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
} else {
    $stmt = $conn->prepare("SELECT * FROM youth_members ORDER BY id DESC");
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Members | Youth Dashboard</title>

<style>
* { box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; }
body { background: #f4f6f8; margin: 0; display: flex; flex-wrap: wrap; }

/* Sidebar */
.sidebar {
    width: 230px;
    background: #003366;
    color: white;
    height: 100vh;
    position: fixed;
    padding-top: 30px;
}
.sidebar h2 { text-align: center; margin-bottom: 25px; }
.sidebar a {
    display: block;
    color: #eee;
    padding: 14px 20px;
    text-decoration: none;
}
.sidebar a:hover { background: #0055aa; padding-left: 30px; }

/* Main content */
.main-content {
    margin-left: 230px;
    width: calc(100% - 230px);
    padding: 25px;
}
header {
    background: #0066cc;
    color: white;
    padding: 18px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}
.logout {
    background: red;
    padding: 6px 14px;
    color: white;
    border-radius: 6px;
    text-decoration: none;
}
.logout:hover { background: darkred; }

/* Search Form */
form {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}
form input { flex: 1; padding: 10px; }
form button {
    padding: 10px 15px;
    background: #0066cc;
    color: white;
    border: none;
    border-radius: 6px;
}
form a {
    padding: 10px 15px;
    background: #aaa;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
th {
    background: #0055aa;
    color: white;
    padding: 12px;
    text-align: left;
}
td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.action-btn {
    padding: 6px 12px;
    border-radius: 5px;
    text-decoration: none;
    color: white;
    font-size: 13px;
}
.edit { background: #ffc107; }
.delete { background: #dc3545; }
.view { background: #28a745; }

/* ✅ MOBILE RESPONSIVE */
@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        text-align: center;
    }
    .main-content {
        margin-left: 0;
        width: 100%;
        padding: 15px;
    }

    /* Table transforms into card layout */
    table, thead, tbody, th, td, tr { display: block; width: 100%; }
    thead tr { display: none; }

    tr {
        margin-bottom: 15px;
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    td {
        border: none;
        padding: 8px 0;
        position: relative;
        padding-left: 45%;
        text-align: left;
    }

    td:before {
        content: attr(data-label);
        position: absolute;
        left: 15px;
        top: 8px;
        font-weight: bold;
        color: #004d99;
    }

    td a.action-btn {
        display: inline-block;
        margin-top: 5px;
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
  <h1>Youth Members</h1>
  <a class="logout" href="logout.php">Logout</a>
</header>

<form method="get">
  <input type="text" name="search" placeholder="Search member..." value="<?php echo htmlspecialchars($search); ?>">
  <button type="submit">Search</button>
  <a href="view_member.php">Reset</a>
</form>

<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Gender</th>
    <th>Phone</th>
    <th>Location</th>
    <th>Date of birth</th>
    <th>Occupation</th>
    <th>Parent</th>
    <th>Document</th>
    <th>Action</th>
  </tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
  <td data-label="ID"><?= $row['id'] ?></td>
  <td data-label="Name"><?= htmlspecialchars($row['full_name']) ?></td>
  <td data-label="Gender"><?= $row['gender'] ?></td>
  <td data-label="Phone"><?= $row['phone_number'] ?></td>
  <td data-label="Location"><?= $row['location'] ?></td>
  <td data-label="Date of birth"><?= $row['birth_date'] ?></td>
  <td data-label="Occupation"><?= $row['occupation'] ?></td>
  <td data-label="Parent"><?= $row['parent_name'] ?> / <?= $row['parent_phone'] ?></td>

  <td data-label="Document">
      <?php if (!empty($row['document']) && file_exists($row['document'])): ?>
          <a class="action-btn view" href="<?= $row['document'] ?>" target="_blank">View</a>
      <?php else: ?>
          No File
      <?php endif; ?>
  </td>

  <td data-label="Action">
      <a class="action-btn edit" href="edit_member.php?id=<?= $row['id'] ?>">Edit</a>
      <a class="action-btn delete" href="delete_member.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this member?')">Delete</a>
  </td>
</tr>
<?php endwhile; ?>
</table>

</div>
</body>
</html>

