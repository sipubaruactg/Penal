<?php
declare(strict_types=1);

/* Database Configuration */
date_default_timezone_set('Asia/Dhaka');

// রেন্ডারের Environment Variables থেকে ডেটা নেওয়া
$host = getenv('DB_HOST') ?: '127.0.0.1';
$user = getenv('DB_USER') ?: '';
$pass = getenv('DB_PASS') ?: '';
$db   = getenv('DB_NAME') ?: '';
$port = getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3306; // পোর্ট যুক্ত করা হলো

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // এখানে $port যুক্ত করা হয়েছে, যা ক্লাউড ডাটাবেজের জন্য জরুরি
    $conn = new mysqli($host, $user, $pass, $db, $port);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // সিস্টেমে ইরর লগ হবে, কিন্তু ইউজার মেইন সিক্রেট দেখতে পাবে না
    error_log("Database Connection Failed: " . $e->getMessage());
    
    // ব্রাউজারে সুন্দর একটি মেসেজ দেখানোর জন্য HTTP Status 500 দেওয়া ভালো
    http_response_code(500);
    exit('<h1>500 Internal Server Error</h1><p>দুঃখিত, ডাটাবেজ সংযোগে সমস্যা হচ্ছে। একটু পর আবার চেষ্টা করুন।</p>');
}

