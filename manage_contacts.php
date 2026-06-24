<?php
/**
 * Contact Management Module - UPDATED FOR TABLE COLUMNS
 */
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

$message = "";

// --- ১. একক ডাটা সেভ / এডিট প্রসেসিং ---
if (isset($_POST['save_contact'])) {
    $id = $_POST['contact_id'];
    $name = $_POST['customer_name'];
    $mobile = $_POST['mobile_number'];
    $email = $_POST['email_id'];
    $location = $_POST['location'];

    if (!empty($id)) {
        // UPDATE: id, customer_name, mobile_number, email_id, location
        $stmt = $conn->prepare("UPDATE customers SET customer_name=?, mobile_number=?, email_id=?, location=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $mobile, $email, $location, $id);
        $stmt->execute();
        $message = "Contact updated!";
    } else {
        // INSERT: customer_name, mobile_number, email_id, location
        $stmt = $conn->prepare("INSERT INTO customers (customer_name, mobile_number, email_id, location) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $mobile, $email, $location);
        $stmt->execute();
        $message = "New contact added!";
    }
}

// --- ২. CSV ইমপোর্ট প্রসেসিং (টেবিলের কলাম অনুযায়ী) ---
if (isset($_POST['import_csv'])) {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
        $file_name = $_FILES['csv_file']['tmp_name'];
        if (($handle = fopen($file_name, "r")) !== FALSE) {
            fgetcsv($handle, 1000, ","); // হেডার স্কিপ
            $stmt = $conn->prepare("INSERT INTO customers (customer_name, mobile_number, email_id, location) VALUES (?, ?, ?, ?)");
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // কলাম অনুযায়ী ডাটা ম্যাপ করা হয়েছে
                $stmt->bind_param("ssss", $data[0], $data[1], $data[2], $data[3]);
                $stmt->execute();
            }
            fclose($handle);
            $message = "Import Successful!";
        }
    }
}

// --- ৩. ডাটা ডিলিট ---
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM customers WHERE id=?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: manage_contacts.php");
    exit();
}

$contacts = $conn->query("SELECT * FROM customers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Contacts</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white max-w-md mx-auto border-x border-gray-800 min-h-screen">

    <header class="bg-gray-900 border-b border-gray-800 p-4 flex justify-between items-center">
        <a href="index.php" class="bg-gray-800 px-4 py-2 rounded-lg text-xs font-bold">BACK</a>
        <div id="date-time" class="text-[10px] text-gray-400 font-bold"></div>
    </header>

    <div class="p-4 bg-gray-900/50 border-b border-gray-800">
        <form action="manage_contacts.php" method="POST" class="space-y-2">
            <input type="hidden" name="contact_id" id="contact_id">
            <input type="text" name="customer_name" id="customer_name" placeholder="Name" required class="w-full bg-gray-950 p-3 rounded-lg text-sm border border-gray-700">
            <input type="text" name="mobile_number" id="mobile_number" placeholder="Mobile" required class="w-full bg-gray-950 p-3 rounded-lg text-sm border border-gray-700">
            <input type="text" name="email_id" id="email_id" placeholder="Email" class="w-full bg-gray-950 p-3 rounded-lg text-sm border border-gray-700">
            <input type="text" name="location" id="location" placeholder="Location" class="w-full bg-gray-950 p-3 rounded-lg text-sm border border-gray-700">
            <button type="submit" name="save_contact" id="submitBtn" class="w-full bg-blue-600 font-bold py-3 rounded-lg text-sm">SAVE CONTACT</button>
        </form>
    </div>

    <div class="p-4 bg-gray-900/30 border-b border-gray-800">
        <form action="manage_contacts.php" method="POST" enctype="multipart/form-data" class="flex gap-2">
            <input type="file" name="csv_file" accept=".csv" required class="text-[10px] w-full bg-gray-900 p-2 rounded border border-gray-700">
            <button type="submit" name="import_csv" class="bg-green-600 px-4 py-2 rounded text-[10px] font-bold">IMPORT</button>
        </form>
    </div>

    <div class="p-4 space-y-3">
        <?php while($row = $contacts->fetch_assoc()): ?>
            <div class="bg-gray-900 p-3 rounded-xl border border-gray-800 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-sm"><?= htmlspecialchars($row['customer_name']) ?></h2>
                    <p class="text-[10px] text-blue-400"><?= htmlspecialchars($row['mobile_number']) ?></p>
                </div>
                <a href="manage_contacts.php?delete=<?= $row['id'] ?>" class="bg-red-600/20 text-red-400 px-3 py-1 text-[10px] font-bold rounded-lg">DEL</a>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        function updateClock() {
            document.getElementById('date-time').innerText = new Date().toLocaleString();
        }
        setInterval(updateClock, 1000); updateClock();
    </script>
</body>
</html>
