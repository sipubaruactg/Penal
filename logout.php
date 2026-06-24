<?php
// ১. সেশন শুরু করা হলো (যদি আগে থেকে শুরু না থাকে)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ২. সেশনের সমস্ত ভেরিয়েবল খালি বা রিমুভ করা হলো
$_SESSION = array();

// ৩. সেশনের কুকি থাকলে তাও ব্রাউজার থেকে মুছে ফেলা হলো (নিরাপত্তার জন্য)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// ৪. সার্ভার থেকে সেশনটি সম্পূর্ণ ধ্বংস করা হলো
session_destroy();

// ৫. সফলভাবে লগআউট শেষে লগইন পেজে রিডাইরেক্ট করা হলো
header("Location: login.php");
exit;
?>
