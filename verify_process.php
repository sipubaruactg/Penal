<?php
// সেশন শুরু করা হলো
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ডাটাবেজ কানেকশন যুক্ত করা
require_once 'config/db.php';

// যদি সেশনে নাম্বার না থাকে, তবে সরাসরি মেইন ফরগেট পেজে ব্যাক করাবে
if (!isset($_SESSION['reset_token_phone'])) {
    header("Location: forget_password.php");
    exit;
}

if (isset($_POST['submit_verify'])) {
    $otp_code = trim($_POST['otp_code']);
    $whatsapp_number = $_SESSION['reset_token_phone'];

    if (empty($otp_code)) {
        $_SESSION['verify_error'] = "ওটিপি কোডটি প্রদান করুন!";
        header("Location: verify_otp.php");
        exit;
    }

    // ডাটাবেজে ওটিপি কোড এবং এর মেয়াদ (Time Expiry) চেক করা হচ্ছে (Prepared Statement)
    $stmt = $conn->prepare("SELECT id FROM system_admins WHERE phone_number = ? AND password_reset_token = ? AND token_expiry > NOW() LIMIT 1");
    $stmt->bind_param("ss", $whatsapp_number, $otp_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // ওটিপি ১০০% সঠিক হলে নতুন পাসওয়ার্ড পেজে অ্যাক্সেস দেওয়ার জন্য একটি সিকিউর মার্কার সেট করা হলো
        $_SESSION['otp_verified'] = true; 
        
        // সরাসরি ফাইনাল পাসওয়ার্ড রিসেট স্ক্রিনে রিডাইরেক্ট
        header("Location: reset_password.php");
        exit;
    } else {
        // কোড ভুল হলে বা টাইম শেষ হয়ে গেলে এরর রিটার্ন করবে
        $_SESSION['verify_error'] = "ভুল ওটিপি কোড অথবা ওটিপির মেয়াদ শেষ হয়ে গেছে!";
        header("Location: verify_otp.php");
        exit;
    }
} else {
    // সরাসরি ফাইল অ্যাক্সেস করতে চাইলে রিডাইরেক্ট
    header("Location: verify_otp.php");
    exit;
}
?>
