<?php
/**
 * Login Processing Module
 * Version: 2.0 (Secure)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    
    $login_input = trim($_POST['login_input']);
    $password = trim($_POST['password']);

    if (empty($login_input) || empty($password)) {
        $_SESSION['login_error'] = "All fields are required!";
        header("Location: login.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, admin_name, username, email, password_hash, role, status FROM system_admins WHERE username = ? OR email = ? OR phone_number = ? LIMIT 1");
    $stmt->bind_param("sss", $login_input, $login_input, $login_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if ($admin['status'] !== 'Active') {
            $_SESSION['login_error'] = "Account is " . htmlspecialchars($admin['status']) . "! Contact system admin.";
            header("Location: login.php");
            exit();
        } 
        
        if (password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['admin_name'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['role'] = $admin['role'];

            $update_stmt = $conn->prepare("UPDATE system_admins SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
            $update_stmt->bind_param("i", $admin['id']);
            $update_stmt->execute();

            header("Location: index.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid password!";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Admin account not found!";
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
