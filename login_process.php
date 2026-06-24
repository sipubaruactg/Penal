<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    
    $login_input = trim($_POST['login_input']);
    $password = trim($_POST['password']);

    if (empty($login_input) || empty($password)) {
        $_SESSION['login_error'] = "সবগুলো ঘর পূরণ করুন!";
        header("Location: login.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, admin_name, username, password_hash, status FROM system_admins WHERE username = ? OR email = ? OR phone_number = ? LIMIT 1");
    $stmt->bind_param("sss", $login_input, $login_input, $login_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if ($admin['status'] !== 'Active') {
            $_SESSION['login_error'] = "অ্যাকাউন্টটি একটিভ নয়!";
            header("Location: login.php");
            exit();
        } 
        
        // এখানে সরাসরি পাসওয়ার্ড চেক করা হচ্ছে (কোনো হ্যাস নেই)
        if ($password === $admin['password_hash']) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['admin_name'];
            $_SESSION['username'] = $admin['username'];

            header("Location: index.php");
            exit();
        } else {
            $_SESSION['login_error'] = "ভুল পাসওয়ার্ড!";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "ইউজার পাওয়া যায়নি!";
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
