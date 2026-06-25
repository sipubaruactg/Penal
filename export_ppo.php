<?php
/**
 * Internet Users Export Module for Excel
 */
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized Access");
}

$filename = "internet_users_list_" . date('Y-m-d') . ".csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

// এক্সেল যেন বাংলা এবং বড় নাম্বার ঠিকঠাক দেখায় তার জন্য BOM যুক্ত করা
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// ১. এক্সেল টেবিলের হেডারসমূহ
fputcsv($output, ['ID', 'Fifi ID', 'User Name', 'Mobile Number', 'Address', 'Package Price', 'Status', 'Created At'], ',');

// ২. ডাটাবেস থেকে সব ডাটা নেওয়া
$query = "SELECT * FROM internet_users ORDER BY id DESC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // ৩. প্রতিটি রো রাইট করা
        fputcsv($output, [
            $row['id'],
            $row['fifi_id'],
            $row['user_name'],
            $row['mobile_number'], // এক্সেল যেন নাম্বারটি সায়েন্টিফিক ফরম্যাটে না নেয়, তাই কোটেশন সহ পাঠাতে পারি
            $row['address'],
            $row['package_price'],
            $row['status'],
            $row['created_at'] // যদি আপনার টেবিলে এই কলামটি থাকে
        ], ',');
    }
}

fclose($output);
exit();
?>
