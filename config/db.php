<?php
/* File: config/db.php - Database Configuration */

// এনভায়রনমেন্ট ভেরিয়েবল যাচাই (যদি কোনোটি মিসিং থাকে তবে এরর দিবে)
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$db   = getenv('DB_NAME');

if (!$host || !$user || !$db) {
    error_log("Database configuration is missing environment variables.");
    die("System configuration error.");
}

// কানেকশন তৈরি (mysqli এরর রিপোর্টিং সেটিং)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    // প্রোডাকশনে এরর লগ করা এবং ইউজারের জন্য নিরাপদ মেসেজ
    error_log("Database connection failed: " . $e->getMessage());
    die("A technical issue occurred. Please check back later.");
}

// টাইমজোন সেট করা
date_default_timezone_set('Asia/Dhaka');
?>
