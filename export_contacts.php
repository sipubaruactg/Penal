<?php
/**
 * Contacts Backup Export Module
 * Version: 1.0 (Secure)
 */
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized Access");
}

$filename = "contacts_backup_" . date('Y-m-d') . ".csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

fputcsv($output, ['Name', 'Mobile Number', 'Email ID', 'Location'], ',');

$query = "SELECT customer_name, mobile_number, email_id, location FROM customers ORDER BY id DESC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row, ',');
    }
}

fclose($output);
exit();
?>
