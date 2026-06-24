<?php
/**
 * Contact Management Module - FULL VERSION (Manual Logic)
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
        $stmt = $conn->prepare("UPDATE customers SET customer_name=?, mobile_number=?, email_id=?, location=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $mobile, $email, $location, $id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO customers (customer_name, mobile_number, email_id, location) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $mobile, $email, $location);
        $stmt->execute();
    }
}

// --- ২. CSV ইমপোর্ট প্রসেসিং ---
if (isset($_POST['import_csv'])) {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
        $file_name = $_FILES['csv_file']['tmp_name'];
        $file_content = file_get_contents($file_name);
        $lines = explode("\n", $file_content);
        array_shift($lines);
        $stmt = $conn->prepare("INSERT INTO customers (customer_name, mobile_number, email_id, location) VALUES (?, ?, ?, ?)");
        foreach ($lines as $line) {
            if (empty(trim($line))) continue;
            $data = str_getcsv($line);
            $stmt->bind_param("ssss", $data[0], $data[1], $data[2], $data[3]);
            $stmt->execute();
        }
        $message = "CSV Import Successful!";
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
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Contact Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .lang-en, .lang-bn { display: none; }
    </style>
</head>
<body class="bg-gray-950 text-white max-w-md mx-auto border-x border-gray-800 min-h-screen">

    <header class="bg-gray-900 border-b border-gray-800 p-4 flex justify-between items-center">
        <a href="index.php" class="bg-gray-800 px-4 py-2 rounded-lg text-sm font-bold">BACK</a>
        <div class="text-center">
            <div id="date-time" class="text-[10px] text-gray-400 font-bold"></div>
        </div>
        <button onclick="toggleLang()" class="bg-indigo-600 px-4 py-1 rounded-lg text-[10px] font-black">BAN / EN</button>
    </header>

    <div class="p-4 bg-gray-900/50 border-b border-gray-800">
        <form action="manage_contacts.php" method="POST" id="contactForm" class="space-y-2">
            <input type="hidden" name="contact_id" id="contact_id">
            <input type="text" name="customer_name" id="customer_name" placeholder="Name" required class="w-full bg-gray-950 p-3 rounded-lg text-sm border border-gray-700 outline-none">
            <input type="text" name="mobile_number" id="mobile_number" placeholder="Mobile" required class="w-full bg-gray-950 p-3 rounded-lg text-sm border border-gray-700 outline-none">
            <button type="submit" name="save_contact" id="submitBtn" class="w-full bg-blue-600 font-bold py-3 rounded-lg text-sm">SAVE CONTACT</button>
        </form>
    </div>

    <script>
        let isBangla = false;
        const bnD = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const bnMonths = ["জানুয়ারি", "ফেব্রুয়ারি", "মার্চ", "এপ্রিল", "মে", "জুন", "জুলাই", "আগস্ট", "সেপ্টেম্বর", "অক্টোবর", "নভেম্বর", "ডিসেম্বর"];

        function updateClock() {
            const now = new Date();
            let h = now.getHours(), m = now.getMinutes(), s = now.getSeconds();
            let d = now.getDate(), mo = now.getMonth();

            if (isBangla) {
                let bnT = `${d.toString().replace(/\d/g, d=>bnD[d])} ${bnMonths[mo]}, ${h.toString().replace(/\d/g, d=>bnD[d])}:${m.toString().replace(/\d/g, d=>bnD[d])}:${s.toString().replace(/\d/g, d=>bnD[d])}`;
                document.getElementById('date-time').innerText = bnT;
            } else {
                document.getElementById('date-time').innerText = `${months[mo]} ${d}, ${h}:${m}:${s}`;
            }
        }
        setInterval(updateClock, 1000); updateClock();

        function toggleLang() {
            isBangla = !isBangla;
            document.getElementById('submitBtn').innerText = isBangla ? "তথ্য জমা দিন" : "SAVE CONTACT";
        }
    </script>
</body>
</html>
