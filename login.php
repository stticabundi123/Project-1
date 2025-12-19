<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Admin Login | St. Matia Mulumba Youth System</title>

  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: linear-gradient(135deg, #0066cc, #004080);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
      padding: 15px;
    }

    .login-container {
      background: #fff;
      padding: 40px 50px;
      border-radius: 14px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      width: 350px;
      max-width: 100%;
      text-align: center;
      animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-15px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .login-container h2 {
      color: #0066cc;
      margin-bottom: 25px;
      font-size: 24px;
    }

    input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
    }

    button {
      width: 100%;
      padding: 12px;
      background: #0066cc;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    button:hover {
      background: #004d99;
    }

    .footer-text {
      margin-top: 20px;
      font-size: 13px;
      color: gray;
    }

    .footer-text a {
      color: #0066cc;
      text-decoration: none;
    }

    .footer-text a:hover {
      text-decoration: underline;
    }

    /* ✅ MOBILE RESPONSIVENESS */
    @media (max-width: 480px) {
      .login-container {
        padding: 25px 20px;
      }

      .login-container h2 {
        font-size: 20px;
      }

      input {
        font-size: 14px;
        padding: 10px;
      }

      button {
        padding: 10px;
        font-size: 15px;
      }

      .footer-text {
        font-size: 12px;
      }
    }
  </style>
</head>

<body>
  <div class="login-container">
    <h2>Admin Login</h2>

    <form action="login_process.php" method="post">
      <input type="text" name="username" placeholder="Enter username" required>
      <input type="password" name="password" placeholder="Enter password" required>
      <button type="submit">Login</button>
    </form>

    <div class="footer-text">
      <p><a href="index.php">← Back to Home</a></p>
      <p>&copy; <?php echo date("Y"); ?> St. Matia Mulumba Youth System</p>
    </div>
  </div>
</body>
</html>


