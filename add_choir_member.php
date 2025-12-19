<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$_SESSION['last_activity'] = time();

// Fetch youth members
$result = mysqli_query($conn, "SELECT id, full_name FROM youth_members ORDER BY full_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Choir Member</title>
<style>
* { font-family: "Segoe UI", Arial; box-sizing: border-box; }
body { display: flex; margin: 0; background: #f4f6f8; }

/* Sidebar */
.sidebar {
    width: 230px;
    background: #003366;
    height: 100vh;
    color: white;
    position: fixed;
    padding-top: 30px;
}
.sidebar a { display:block; padding:14px 25px; text-decoration:none; color:#e1e1e1; transition:0.3s; }
.sidebar a:hover { background:#0055aa; padding-left:30px; }

/* Main content */
.main-content { margin-left:230px; padding:25px; width:100%; }
header { background:#0066cc; color:white; padding:18px; border-radius:8px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; }
.logout { background:red; color:white; padding:8px 14px; border-radius:6px; text-decoration:none; }

/* Content box */
.content { background:white; margin-top:20px; padding:20px; border-radius:8px; }

/* Form */
form { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
label { font-weight:bold; }
select, button { padding:10px; border:1px solid #ccc; border-radius:6px; width:100%; }
button { grid-column:span 2; background:#0066cc; color:white; cursor:pointer; transition:0.2s; }
button:hover { background:#0055aa; }

/* Voice dropdown initially hidden */
#voiceDiv { display:none; }

/* Responsive adjustments */
@media (max-width:768px){
    body { flex-direction: column; }
    .sidebar { width:100%; height:auto; position:relative; }
    .main-content { margin-left:0; padding:15px; }
    form { grid-template-columns:1fr; }
    button { grid-column:1; }
}
</style>
</head>
<body>

<div class="sidebar">
    <h2 style="text-align:center;">🎶 Choir</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="add_choir_member.php">➕ Add Choir Member</a>
    <a href="view_choir_member.php">🎤 View Choir</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main-content">
<header>
    <h1>Add Choir Member</h1>
    <a href="logout.php" class="logout">Logout</a>
</header>

<div class="content">
<form action="save_choir_member.php" method="post">

    <div>
        <label>Select Youth Member</label>
        <select name="youth_id" required>
            <option value="">-- Select Member --</option>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['full_name']); ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div>
        <label>Select Role</label>
        <select name="role" id="role" required onchange="toggleVoice()">
            <option value="">-- Choose Role --</option>
            <option value="Choir Teacher">Choir Teacher</option>
            <option value="Singer">Singer</option>
            <option value="Youth Moderator">Youth Moderator</option>
            <option value="Vice">Vice</option>
            <option value="Secretary">Secretary</option>
            <option value="Vice Secretary">Vice Secretary</option>
            <option value="Treasury">Treasury</option>
            <option value="Voice Representative">Voice Representative</option>
        </select>
    </div>

    <div id="voiceDiv">
        <label>Select Voice</label>
        <select name="voice_type" id="voice_type">
            <option value="">-- Choose Voice --</option>
            <option value="Soprano">Soprano</option>
            <option value="Alto">Alto</option>
            <option value="Tenor">Tenor</option>
            <option value="Bass">Bass</option>
        </select>
    </div>

    <button type="submit">Save Choir Member</button>

</form>
</div>
</div>

<script>
function toggleVoice() {
    const role = document.getElementById("role").value;
    const voiceDiv = document.getElementById("voiceDiv");

    if (role === "Voice Representative") {
        voiceDiv.style.display = "block";
        document.getElementById("voice_type").required = true;
    } else {
        voiceDiv.style.display = "none";
        document.getElementById("voice_type").required = false;
    }
}
</script>

</body>
</html>


