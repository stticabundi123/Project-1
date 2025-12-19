<?php
session_start();
?>

<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>St. Matia Mulumba Youth System</title>

  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: #f2f4f7;
      margin: 0;
      padding: 0;
      color: #333;
    }

    header {
      background: #0066cc;
      color: white;
      padding: 25px 0;
      text-align: center;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    header h1 {
      margin: 0;
      font-size: 28px;
      letter-spacing: 1px;
    }

    header p {
      margin: 5px 0 0;
      font-size: 16px;
      color: #e1e1e1;
    }

    .container {
      max-width: 800px;
      background: white;
      margin: 60px auto;
      padding: 40px;
      border-radius: 14px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      text-align: center;
      line-height: 1.6;
    }

    h2 {
      color: #0055aa;
    }

    a {
      display: inline-block;
      margin: 12px;
      padding: 12px 20px;
      background: #0066cc;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      transition: background 0.3s ease;
    }

    a:hover {
      background: #004d99;
    }

    footer {
      text-align: center;
      padding: 20px;
      color: gray;
      background: #f2f2f2;
      margin-top: 50px;
      font-size: 14px;
    }

    /* ✅ MOBILE RESPONSIVENESS */
    @media (max-width: 768px) {

      header h1 {
        font-size: 22px;
      }

      header p {
        font-size: 14px;
      }

      .container {
        margin: 20px;
        padding: 20px;
      }

      a {
        width: 100%;     /* Full width buttons */
        padding: 14px;
        margin: 8px 0;
        box-sizing: border-box;
      }

      h2 {
        font-size: 20px;
      }

      p {
        font-size: 15px;
      }
    }
  </style>
</head>

<body>

<header>
  <h1>St. Matia Mulumba Youth Database System</h1>
  <p>Empowering the Youth Through Technology and Faith</p>
</header>

<div class="container">
  <h2>Welcome to the Youth Information Management System</h2>
  <p>
    This system is developed for <strong>St. Matia Mulumba Catholic Church</strong> to help manage and organize
    the records of our youth members. It allows easy access to member information such as
    contact details, joining dates, and other relevant data for smooth coordination and record keeping.
  </p>

  <p>
    The project is created and maintained by the <strong>Church Youth Team</strong> as part of our mission
    to embrace technology in faith-based leadership and community service.
  </p>

  <p>
    Only authorized administrators are allowed to add, edit, or view member details.
    If you are an admin, please log in below to manage youth records.
  </p>

  <div>
    <a href="login.php">Admin Login</a>
    <a href="add_member.php">Add New Member</a>
    <a href="view_member.php">View Members</a>
  </div>
</div>

<footer>
  <p>&copy; <?php echo date("Y"); ?> St. Matia Mulumba Youth Project | Developed by Scholasttica Bundi (+254758788352)</p>
</footer>

</body>
</html>

