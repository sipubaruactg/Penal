<?php
/**
 * Contact Management Module
 * Version: 2.0 (Secure)
 */
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$error_message = "";

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
        if ($stmt->execute()) $message = "Updated successfully!";
    } else {
        $stmt = $conn->prepare("INSERT INTO customers (customer_name, mobile_number, email_id, location) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $mobile, $email, $location);
        if ($stmt->execute()) $message = "New contact added!";
    }
}

// --- ২. CSV ইমপোর্ট প্রসেসিং ---
if (isset($_POST['import_csv'])) {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
        $file_name = $_FILES['csv_file']['tmp_name'];
        $file_content = file_get_contents($file_name);
        $bom = pack('H*','EFBBBF');
        $file_content = preg_replace("/^$bom/", '', $file_content);
        
        $delimiter = (strpos($file_content, ';') !== false) ? ";" : ",";
        $lines = explode("\n", $file_content);
        if (!empty($lines)) {
            $headers = str_getcsv(array_shift($lines), $delimiter);
            $name_idx = 0; $phone_idx = 1; $email_idx = 2; $location_idx = 3;

            $stmt = $conn->prepare("INSERT INTO customers (customer_name, mobile_number, email_id, location) VALUES (?, ?, ?, ?)");
            foreach ($lines as $line) {
                if (empty(trim($line))) continue;
                $data = str_getcsv($line, $delimiter);
                $name = $data[$name_idx] ?? '';
                $mobile = preg_replace('/[^\d+]/', '', $data[$phone_idx] ?? '');
                $email = $data[$email_idx] ?? '';
                $location = $data[$location_idx] ?? '';

                if (!empty($name) && !empty($mobile)) {
                    $stmt->bind_param("ssss", $name, $mobile, $email, $location);
                    $stmt->execute();
                }
            }
            $message = "Import successful!";
        }
    }
}

// --- ৩. ডাটা ডিলিট প্রসেসিং ---
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Contact Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { -webkit-touch-callout: none; user-select: none; overflow: hidden; position: fixed; width: 100%; height: 100%; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-gray-950 text-white font-sans flex flex-col h-screen max-w-md mx-auto border-x border-gray-800">

    <header class="bg-gray-900 border-b border-gray-800 py-4 px-4 text-center shrink-0">
        <h1 class="text-lg font-black uppercase tracking-widest text-blue-400">Contacts Hub</h1>
        <?php if($message): ?><p class="text-[10px] text-emerald-400 mt-1"><?= $message ?></p><?php endif; ?>
    </header>

    <div class="p-4 bg-gray-900/50 border-b border-gray-800 shrink-0">
        <form action="manage_contacts.php" method="POST" id="contactForm" class="space-y-2">
            <input type="hidden" name="contact_id" id="contact_id">
            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="customer_name" id="customer_name" placeholder="Name" required class="w-full bg-gray-950 text-sm p-3 rounded-lg border border-gray-800 outline-none focus:border-blue-500">
                <input type="text" name="mobile_number" id="mobile_number" placeholder="Mobile" required class="w-full bg-gray-950 text-sm p-3 rounded-lg border border-gray-800 outline-none focus:border-blue-500">
            </div>
            <input type="email" name="email_id" id="email_id" placeholder="Email (Optional)" class="w-full bg-gray-950 text-sm p-3 rounded-lg border border-gray-800 outline-none focus:border-blue-500">
            <input type="text" name="location" id="location" placeholder="Address/Location" class="w-full bg-gray-950 text-sm p-3 rounded-lg border border-gray-800 outline-none focus:border-blue-500">
            <button type="submit" name="save_contact" id="submitBtn" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg text-sm active:scale-95 transition-all">SAVE CONTACT</button>
        </form>
    </div>

    <div class="flex-1 overflow-y-auto p-4 space-y-3 no-scrollbar bg-gray-950">
        <?php while($row = $contacts->fetch_assoc()): ?>
            <div class="bg-gray-900 p-4 rounded-xl border border-gray-800 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-sm text-gray-200"><?= htmlspecialchars($row['customer_name']) ?></h2>
                    <p class="text-[10px] text-blue-400 font-mono"><?= htmlspecialchars($row['mobile_number']) ?></p>
                </div>
                <div class="flex space-x-2">
                    <button onclick='editContact(<?= json_encode($row) ?>)' class="bg-amber-600/20 text-amber-400 px-3 py-1 text-[10px] font-bold rounded-lg">EDIT</button>
                    <a href="manage_contacts.php?delete=<?= $row['id'] ?>" class="bg-red-600/20 text-red-400 px-3 py-1 text-[10px] font-bold rounded-lg">DEL</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        function editContact(data) {
            document.getElementById('contact_id').value = data.id;
            document.getElementById('customer_name').value = data.customer_name;
            document.getElementById('mobile_number').value = data.mobile_number;
            document.getElementById('email_id').value = data.email_id;
            document.getElementById('location').value = data.location;
            document.getElementById('submitBtn').innerText = "UPDATE CONTACT";
        }
    </script>
</body>
</html>
