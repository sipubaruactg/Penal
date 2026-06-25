<?php
/**
 * Internet Users Export Module (Updated with Dates)
 */
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized Access");
}

$filename = "internet_users_full_backup_" . date('Y-m-d') . ".csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

// এক্সেল যেন বাংলা এবং UTF-8 ক্যারেক্টার ঠিকঠাক দেখায় তার জন্য BOM যুক্ত করা
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// ১. এক্সেল টেবিলের হেডারসমূহ (নতুন কলামসহ)
fputcsv($output, [
    'ID', 
    'Mikrotik Username', 
    'Full Name', 
    'Mobile Number', 
    'Address', 
    'Package Name', 
    'Package Price', 
    'Status', 
    'Activation Date', 
    'Expiry Date'
], ',');

// ২. ডাটাবেস থেকে সব ডাটা নেওয়া
$query = "SELECT * FROM internet_users ORDER BY id DESC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // ৩. প্রতিটি রো রাইট করা
        fputcsv($output, [
            $row['id'],
            $row['mikrotik_username'],
            $row['full_name'],
            $row['mobile_number'],
            $row['address'],
            $row['package_name'],
            $row['package_price'],
            $row['status'],
            $row['activation_date'],
            $row['expiry_date']
        ], ',');
    }
}

fclose($output);
exit();
?>
