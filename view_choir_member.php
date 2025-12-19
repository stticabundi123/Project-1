<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$search = $_GET['search'] ?? "";
$like = "%$search%";

$sql = "
    SELECT choir_members.id AS choir_id, choir_members.role,
           youth_members.id AS youth_id, youth_members.full_name, youth_members.gender,
           youth_members.phone_number, youth_members.location, youth_members.parent_name,
           youth_members.parent_phone, youth_members.document
    FROM choir_members
    INNER JOIN youth_members ON choir_members.youth_id = youth_members.id
";

if ($search !== "") {
    $sql .= " WHERE youth_members.full_name LIKE ? OR youth_members.phone_number LIKE ? OR choir_members.role LIKE ?";
    $sql .= " ORDER BY choir_members.id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql .= " ORDER BY choir_members.id DESC";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Choir Members | Dashboard</title>
<style>
* { box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; margin: 0; padding: 0; }
body { background: #f4f6f8; display: flex; flex-wrap: wrap; }

/* Sidebar */
.sidebar {
    width: 230px;
    background: #003366;
    color: white;
    position: fixed;
    height: 100vh;
    padding-top: 30px;
}
.sidebar a { display:block; color:#eee; padding:14px 25px; text-decoration:none; }
.sidebar a:hover { background:#0055aa; padding-left:30px; }

/* Main content */
.main-content { margin-left:230px; padding:25px; width:calc(100% - 230px); }
header { background:#0066cc; color:white; padding:18px; border-radius:10px; margin-bottom:20px; display:flex; justify-content:space-between; flex-wrap:wrap; }
.logout { background:red; padding:6px 14px; color:white; border-radius:6px; text-decoration:none; }

/* Search bar */
form { display:flex; gap:10px; margin-bottom:15px; flex-wrap: wrap; }
form input { flex:1; padding:10px; border-radius:6px; border:1px solid #ccc; min-width:150px; }
form button { padding:10px 15px; background:#0066cc; color:white; border:none; border-radius:6px; }
form a { background:#777; padding:10px 15px; text-decoration:none; border-radius:6px; color:white; }

/* Table */
table {
    width:100%;
    border-collapse:collapse;
    background:white;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
}
th { background:#0055aa; color:white; padding:12px; text-align:left; }
td { padding:10px; border-bottom:1px solid #ddd; }
.action-btn { padding:6px 12px; border-radius:5px; color:white; text-decoration:none; }
.delete { background:#dc3545; }
.view { background:#28a745; }

/* Responsive Table */
@media (max-width: 768px) {
    .sidebar { width:100%; height:auto; position:relative; }
    .main-content { margin-left:0; width:100%; padding:15px; }

    table, thead, tbody, th, td, tr { display:block; width:100%; }
    thead tr { display:none; }

    tr { margin-bottom:15px; background:white; padding:10px; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
    td { border:none; padding:6px 0; position: relative; padding-left:50%; }
    td:before { 
        content: attr(data-label); 
        position: absolute; 
        left:10px; 
        top:6px; 
        font-weight:bold; 
        color:#004d99; 
    }
}
</style>
</head>
<body>

<div class="sidebar">
    <h2 style="text-align:center;">🎶 Choir Panel</h2>
    <a href="dashboard.php">🏠 Home</a>
    <a href="add_choir_member.php">➕ Add Choir Member</a>
    <a href="view_choir_member.php">🎤 View Choir</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main-content">
<header>
    <h1>Choir Members</h1>
    <a href="logout.php" class="logout">Logout</a>
</header>

<form method="get">
    <input type="text" name="search" placeholder="Search choir member..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
    <a href="view_choir_member.php">Reset</a>
</form>

<table>
<tr>
    <th>Name</th>
    <th>Role</th>
    <th>Phone</th>
    <th>Location</th>
    <th>Parent</th>
    <th>Document</th>
    <th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td data-label="Name"><?= htmlspecialchars($row['full_name']) ?></td>
    <td data-label="Role"><?= htmlspecialchars($row['role']) ?></td>
    <td data-label="Phone"><?= $row['phone_number'] ?></td>
    <td data-label="Location"><?= $row['location'] ?></td>
    <td data-label="Parent"><?= $row['parent_name'] ?> / <?= $row['parent_phone'] ?></td>
    <td data-label="Document">
        <?php if (!empty($row['document']) && file_exists($row['document'])): ?>
            <a class="action-btn view" href="<?= $row['document'] ?>" target="_blank">View</a>
        <?php else: ?>
            No File
        <?php endif; ?>
    </td>
    <td data-label="Action">
        <a class="action-btn delete" href="delete_choir_member.php?id=<?= $row['choir_id'] ?>" onclick="return confirm('Remove from choir?')">Remove</a>
    </td>
</tr>
<?php endwhile; ?>

</table>
</div>
</body>
</html>


