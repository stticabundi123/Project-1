<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => false,  // set true if using HTTPS
        'httponly' => true
    ]);
    session_start();
}

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Auto logout after 30 minutes
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_destroy();
    header("Location: login.php?error=Session expired. Please login again.");
    exit();
}
$_SESSION['last_activity'] = time();

include 'db_connect.php';

// Count youth members
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM youth_members");
$row = mysqli_fetch_assoc($result);
$total_members = $row['total'];

// Count choir members
$result_choir = mysqli_query($conn, "SELECT COUNT(*) AS total FROM choir_members");
$row_choir = mysqli_fetch_assoc($result_choir);
$total_choir = $row_choir['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | St. Matia Mulumba Youth System</title>
<style>
/* Reset & general */
* { box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; margin: 0; padding: 0; }
body { background: #f4f6f8; display: flex; flex-wrap: wrap; min-height: 100vh; }

/* Sidebar */
.sidebar {
    width: 230px;
    background: #003366;
    height: 100vh;
    color: white;
    position: fixed;
    padding-top: 30px;
    box-shadow: 2px 0 6px rgba(0,0,0,0.1);
}
.sidebar h2, .sidebar h3 { color: #fff; }
.sidebar h2 { text-align: center; margin-bottom: 30px; font-size: 20px; }
.sidebar h3 { padding-left: 25px; margin-top:20px; font-size: 16px; }
.sidebar a { display: block; color: #e1e1e1; padding: 14px 25px; text-decoration: none; transition: 0.3s; }
.sidebar a:hover { background: #0055aa; color: #fff; padding-left: 30px; }

/* Main content */
.main-content { margin-left: 230px; width: calc(100% - 230px); padding: 30px; display: flex; flex-direction: column; }
header { background: #0066cc; color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
header h1 { font-size: 24px; margin-bottom: 10px; }
.logout { background: red; padding: 8px 16px; color: white; border-radius: 6px; text-decoration: none; }
.logout:hover { background: darkred; }

/* Dashboard cards */
.dashboard-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px; }
.card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); transition: transform 0.2s; }
.card:hover { transform: translateY(-5px); }
.card h3 { font-size: 20px; margin-bottom: 10px; color: #0055aa; }
.card p { font-size: 28px; font-weight: bold; color: #333; }

/* Welcome message */
.welcome { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); margin-top: 20px; }
.welcome p { font-size: 16px; color: #333; margin-bottom: 10px; }

/* Footer */
footer { text-align: center; padding: 15px; background: #f2f2f2; color: #555; font-size: 14px; width: 100%; border-top: 1px solid #ddd; margin-top: auto; }

/* Responsive adjustments */
@media (max-width: 768px) {
    body { flex-direction: column; }
    .sidebar { width: 100%; height: auto; position: relative; padding-bottom: 10px; }
    .main-content { margin-left: 0; width: 100%; padding: 15px; }
    .dashboard-cards { grid-template-columns: 1fr; }
    header { flex-direction: column; align-items: flex-start; }
    header h1 { margin-bottom: 10px; }
}
</style>
</head>
<body>

<div class="sidebar">
    <h2>🧭 Dashboard</h2>

    <h3 style="padding-left: 25px; margin-top:20px;">Youth Members</h3>
    <a href="add_member.php">➕ Add Member</a>
    <a href="view_member.php">👥 View Members</a>
    <a href="view_member.php">🔍 Search Member</a>

    <h3 style="padding-left: 25px; margin-top:20px;">Choir Members</h3>
    <a href="add_choir_member.php">➕ Add Choir Member</a>
    <a href="view_choir_member.php">👥 View Choir Members</a>
    <a href="view_choir_member.php">🔍 Search Choir Member</a>

    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main-content">
<header>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin']); ?>!</h1>
    <a href="logout.php" class="logout">Logout</a>
</header>

<div class="welcome">
    <p>Welcome to the St. Matia Mulumba Youth Database Management Dashboard.</p>
    <p>Use the sidebar to navigate between youth and choir members.</p>
</div>

<div class="dashboard-cards">
    <div class="card">
        <h3>Total Youth Members</h3>
        <p><?php echo $total_members; ?></p>
    </div>
    <div class="card">
        <h3>Total Choir Members</h3>
        <p><?php echo $total_choir; ?></p>
    </div>
    <div class="card">
        <h3>Add Member</h3>
        <p><a href="add_member.php" style="text-decoration:none; color:#0066cc;">Go ➔</a></p>
    </div>
    <div class="card">
        <h3>View Members</h3>
        <p><a href="view_member.php" style="text-decoration:none; color:#0066cc;">Go ➔</a></p>
    </div>
    <div class="card">
        <h3>Add Choir Member</h3>
        <p><a href="add_choir_member.php" style="text-decoration:none; color:#0066cc;">Go ➔</a></p>
    </div>
    <div class="card">
        <h3>View Choir Members</h3>
        <p><a href="view_choir_member.php" style="text-decoration:none; color:#0066cc;">Go ➔</a></p>
    </div>
</div>

<footer>
  &copy; <?php echo date("Y"); ?> St. Matia Mulumba Youth Group | Serving Christ through Technology
</footer>

</div>
</body>
</html>

