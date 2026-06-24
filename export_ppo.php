<?php
// ডাটাবেজ কানেকশন ফাইল যুক্ত করা হলো
require_once 'config/db.php';

// ফাইলের নাম নির্ধারণ (যেমন: ppo_backup_2026-06-24.csv)
$filename = "ppo_backup_" . date('Y-m-d') . ".csv";

// ব্রাউজারকে ফাইলটি সরাসরি ডাউনলোড করার নির্দেশ দেওয়া (Headers)
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// আউটপুট স্ট্রিম ওপেন করা
$output = fopen('php://output', 'w');

// ফাইলের শুরুতে UTF-8 BOM যুক্ত করা যাতে বাংলা ফন্ট ভেঙে না যায়
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// পিপিও ফরম্যাটের স্ট্যান্ডার্ড হেডার কলাম (পাইপ ডেলিমিটারের জন্য)
fputcsv($output, array('Name', 'Mobile', 'Email', 'Location'), '|');

// ডাটাবেজ থেকে কন্টাক্ট তুলে আনা
$query = "SELECT customer_name, mobile_number, email_id, location FROM customers ORDER BY id DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // প্রতিটি রো পাইপ (|) ডেলিমিটার দিয়ে আলাদা করে ফাইলে লেখা হচ্ছে
        fputcsv($output, $row, '|');
    }
}

fclose($output);
exit;
?>
