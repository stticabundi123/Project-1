<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => false,  // set true if you use HTTPS
        'httponly' => true
    ]);
    session_start();
}

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true); // prevent session fixation
            $_SESSION['admin'] = $username;
            $_SESSION['last_activity'] = time(); // for session timeout
            header("Location: dashboard.php");
            exit();
        } else {
            header("Location: login.php?error=Invalid Username or Password");
            exit();
        }
    } else {
        header("Location: login.php?error=Invalid Username or Password");
        exit();
    }
}
?>
