<?php
// ১. সেশন শুরু করা হলো
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// যদি ইতিমধ্যেই লগইন করা থাকে, তবে সরাসরি ড্যাশবোর্ডে পাঠিয়ে দেবে
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

// ২. ডাটাবেজ কানেকশন যুক্ত করা
require_once 'config/db.php';

// ৩. ডাটা প্রসেসিং শুরু
if (isset($_POST['login'])) {
    
    // ইনপুট ডাটা রিসিভ ও ক্লিন করা
    $login_input = trim($_POST['login_input']);
    $password = trim($_POST['password']);

    // ফিল্ড খালি আছে কিনা চেক
    if (empty($login_input) || empty($password)) {
        $_SESSION['login_error'] = "সবগুলো ফিল্ড সঠিকভাবে পূরণ করুন!";
        header("Location: login.php");
        exit;
    }

    // ৪. ডাটাবেজ কোয়েরি (Prepared Statement - SQL Injection প্রটেকশন)
    // ইউজারনেম, ইমেইল অথবা হোয়াটসঅ্যাপ/ফোন নাম্বার (phone_number)—যেকোনো একটি মিললেই ডাটা আসবে
    $stmt = $conn->prepare("SELECT id, admin_name, username, email, password_hash, role, status FROM system_admins WHERE username = ? OR email = ? OR phone_number = ? LIMIT 1");
    $stmt->bind_param("sss", $login_input, $login_input, $login_input);
    $stmt->execute();
    $result = $stmt->get_result();

    // ৫. এডমিন অ্যাকাউন্ট যাচাইকরণ
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // অ্যাকাউন্ট একটিভ আছে কিনা চেক
        if ($admin['status'] !== 'Active') {
            $_SESSION['login_error'] = "আপনার অ্যাকাউন্টটি " . $admin['status'] . " অবস্থায় আছে! এডমিনের সাথে যোগাযোগ করুন।";
            header("Location: login.php");
            exit;
        } 
        
        // পাসওয়ার্ড হ্যাশ যাচাইকরণ (password_verify)
        if (password_verify($password, $admin['password_hash'])) {
            
            // সেশন ভ্যালু সেট করা (যা ড্যাশবোর্ড ও হেডারে শো করবে)
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['admin_name'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['role'] = $admin['role'];

            // শেষ লগইন টাইম আপডেট করা
            $update_stmt = $conn->prepare("UPDATE system_admins SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
            $update_stmt->bind_param("i", $admin['id']);
            $update_stmt->execute();

            // ৬. সরাসরি ড্যাশবোর্ডে পাঠানো
            header("Location: dashboard.php");
            exit;
            
        } else {
            $_SESSION['login_error'] = "ভুল পাসওয়ার্ড! আবার চেষ্টা করুন।";
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['login_error'] = "এই তথ্য দিয়ে কোনো এডমিন অ্যাকাউন্ট পাওয়া যায়নি!";
        header("Location: login.php");
        exit;
    }
} else {
    // সরাসরি কেউ এই ফাইলে অ্যাক্সেস করার চেষ্টা করলে লগইন পেজে পাঠিয়ে দেবে
    header("Location: login.php");
    exit;
}
?>
