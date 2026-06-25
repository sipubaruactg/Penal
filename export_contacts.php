<?php
/**
 * Contacts Export Module
 * এই ফাইলটি আপনার কন্টাক্ট লিস্টকে CSV ফরম্যাটে ডাউনলোড করবে।
 */
require_once 'config/db.php';

// সেশন চেক
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized Access");
}

// ফাইলের নাম সেট করা
$filename = "contacts_export_" . date('Y-m-d') . ".csv";

// হেডার সেট করা যাতে ব্রাউজার ফাইলটি ডাউনলোড করে
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// আউটপুট স্ট্রিম ওপেন করা
$output = fopen('php://output', 'w');

// বাংলা ক্যারেক্টার সাপোর্ট করার জন্য BOM যোগ করা
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// ১. CSV হেডার (Name এবং Phone 1 - Value)
fputcsv($output, ['Name', 'Phone 1 - Value']);

// ২. ডাটাবেস থেকে ডাটা ফেচ করা
$query = "SELECT customer_name, mobile_number FROM customers ORDER BY id DESC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // ৩. নাম এবং নাম্বার রাইট করা
        fputcsv($output, [$row['customer_name'], $row['mobile_number']]);
    }
}

// ফাইল ক্লোজ করে এক্সিট
fclose($output);
exit();
?>
