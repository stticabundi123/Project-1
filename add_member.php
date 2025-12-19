<?php
session_start();

// Only allow logged-in admins
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Show success message if redirected from save_member.php
$success = false;
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Member | St. Matia Mulumba Youth</title>

  <style>
    * { box-sizing: border-box; font-family: "Segoe UI", Arial, sans-serif; margin: 0; padding: 0; }
    body { background: #f4f6f8; display: flex; }
    .sidebar { width: 230px; background: #003366; height: 100vh; color: white; position: fixed; padding-top: 30px; box-shadow: 2px 0 6px rgba(0,0,0,0.1); }
    .sidebar h2 { text-align: center; margin-bottom: 30px; font-size: 20px; color: #fff; }
    .sidebar a { display: block; color: #e1e1e1; padding: 14px 25px; text-decoration: none; transition: 0.3s; }
    .sidebar a:hover { background: #0055aa; color: #fff; padding-left: 30px; }
    .main-content { margin-left: 230px; width: calc(100% - 230px); padding: 30px; }
    header { background: #0066cc; color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); }
    header h1 { font-size: 24px; }
    .logout { background: red; padding: 8px 16px; color: white; border-radius: 6px; text-decoration: none; float: right; margin-top: -38px;}
    .logout:hover { background: darkred; }
    .content { margin-top: 30px; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); }
    .content h2 { color: #0055aa; margin-bottom: 15px; }
    form { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    label { display: block; font-weight: bold; margin-bottom: 5px; }
    input, select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; }
    button { grid-column: span 2; padding: 12px; background: #0066cc; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; }
    button:hover { background: #004d99; }
    .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #c3e6cb; }
    footer { text-align: center; padding: 15px; background: #f2f2f2; color: #555; font-size: 14px; position: fixed; bottom: 0; left: 0; width: 100%; border-top: 1px solid #ddd; }

    /* ✅ MOBILE RESPONSIVE FIX (Safe Version / No Breakage) */
    @media (max-width: 768px) {

      body { flex-direction: column; overflow-x: hidden; }

      .sidebar {
        width: 100% !important;
        height: auto !important;
        position: relative !important;
        padding: 10px 0 !important;
        text-align: center;
      }

      .sidebar a { padding: 12px !important; }

      .main-content {
        margin-left: 0 !important;
        width: 100% !important;
        padding: 15px !important;
      }

      form {
        display: block !important;
      }

      input, select, button {
        width: 100% !important;
        box-sizing: border-box;
      }

      .content {
        overflow-x: auto;
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
    <h1>Add New Youth Member</h1>
    <a href="logout.php" class="logout">Logout</a>
  </header>

  <div class="content">
    <?php if ($success): ?>
      <div class="success">✅ Member added successfully!</div>
    <?php endif; ?>

    <h2>Member Registration Form</h2>

    <form action="save_member.php" method="post" enctype="multipart/form-data">
      <div>
        <label>Full Name</label>
        <input type="text" name="full_name" required>
      </div>

      <div>
        <label>Gender</label>
        <select name="gender" required>
          <option value="">-- Select Gender --</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>

      <div>
        <label>Phone Number</label>
        <input type="text" name="phone_number" required>
      </div>

      <div>
        <label>Home Parish</label>
        <input type="text" name="location" required>
      </div>

      <div>
        <label>Date of Birth </label>
        <input type="date" name="DOB" required>
      </div>

      <div>
        <label>Occupation</label>
        <input type="text" name="occupation">
      </div>

      <div>
        <label>Parent Name</label>
        <input type="text" name="parent_name" required>
      </div>

      <div>
        <label>Relationship (e.g., Mother, Father, Guardian)</label>
        <input type="text" name="relationship" required>
      </div>

      <div>
        <label>Parent Phone</label>
        <input type="text" name="parent_phone" required>
      </div>

      <div>
        <label>Upload Document (ID, Passport, or Baptism Card)</label>
        <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" required>
      </div>

      <button type="submit">Save Member</button>
    </form>
  </div>
</div>

<footer>
  <p>&copy; <?php echo date("Y"); ?> St. Matia Mulumba Youth Group | Serving Christ through Technology</p>
</footer>

</body>
</html>




