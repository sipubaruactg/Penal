<?php
/**
 * Contact Management Module - FULL VERSION (All Fields Included)
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
        $message = "Updated successfully!";
    } else {
        $stmt = $conn->prepare("INSERT INTO customers (customer_name, mobile_number, email_id, location) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $mobile, $email, $location);
        $stmt->execute();
        $message = "New contact added!";
    }
}

// --- ২. CSV ইমপোর্ট প্রসেসিং ---
if (isset($_POST['import_csv'])) {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
        $file_name = $_FILES['csv_file']['tmp_name'];
        if (($handle = fopen($file_name, "r")) !== FALSE) {
            fgetcsv($handle, 1000, ","); 
            $stmt = $conn->prepare("INSERT INTO customers (customer_name, mobile_number, email_id, location) VALUES (?, ?, ?, ?)");
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // ইনডেক্স ঠিক রাখা হয়েছে (0:name, 1:mobile, 2:email, 3:location)
                $stmt->bind_param("ssss", $data[0], $data[1], $data[2], $data[3]);
                $stmt->execute();
            }
            fclose($handle);
            $message = "CSV Import Successful!";
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
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white max-w-md mx-auto border-x border-gray-800 min-h-screen">

    <header class="bg-gray-900 border-b border-gray-800 p-4 flex justify-between items-center">
        <a href="index.php" class="bg-gray-800 px-4 py-2 rounded-lg text-xs font-bold">BACK</a>
        <div class="text-center">
            <div id="date-part" class="text-[9px] text-gray-400 font-bold uppercase"></div>
            <div id="clock-part" class="text-[11px] text-emerald-400 font-bold"></div>
        </div>
        <button onclick="toggleLang()" class="bg-indigo-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase">BAN / EN</button>
    </header>

    <div class="p-4 bg-gray-900/50 border-b border-gray-800">
        <h1 id="title" class="text-sm font-black text-blue-400 mb-3 uppercase tracking-widest">MANAGE CONTACTS</h1>
        <form action="manage_contacts.php" method="POST" id="contactForm" class="space-y-2">
            <input type="hidden" name="contact_id" id="contact_id">
            <input type="text" name="customer_name" id="customer_name" placeholder="Name" required class="w-full bg-gray-950 p-3 rounded-lg text-sm border border-gray-700 outline-none">
            <input type="text" name="mobile_number" id="mobile_number" placeholder="Mobile" required class="w-full bg-gray-950 p-3 rounded-lg text-sm border border-gray-700 outline-none">
            <input type="text" name="email_id" id="email_id" placeholder="Email (Optional)" class="w-full bg-gray-950 p-3 rounded-lg text-sm border border-gray-700 outline-none">
            <input type="text" name="location" id="location" placeholder="Address/Location" class="w-full bg-gray-950 p-3 rounded-lg text-sm border border-gray-700 outline-none">
            <button type="submit" name="save_contact" id="submitBtn" class="w-full bg-blue-600 font-bold py-3 rounded-lg text-sm">SAVE CONTACT</button>
        </form>
    </div>

    <div class="p-4 border-b border-gray-800 bg-gray-900/30">
        <form action="manage_contacts.php" method="POST" enctype="multipart/form-data" class="flex gap-2">
            <input type="file" name="csv_file" accept=".csv" required class="text-[10px] w-full bg-gray-900 p-2 rounded border border-gray-700">
            <button type="submit" name="import_csv" class="bg-green-600 px-4 py-2 rounded text-[10px] font-bold uppercase">IMPORT</button>
        </form>
    </div>

    <div class="p-4 space-y-3 pb-10">
        <?php while($row = $contacts->fetch_assoc()): ?>
            <div class="bg-gray-900 p-3 rounded-xl border border-gray-800 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-sm text-gray-200"><?= htmlspecialchars($row['customer_name']) ?></h2>
                    <p class="text-[10px] text-blue-400 font-mono"><?= htmlspecialchars($row['mobile_number']) ?></p>
                </div>
                <div class="flex gap-2">
                    <button onclick='editContact(<?= json_encode($row) ?>)' class="bg-amber-600/20 text-amber-400 px-3 py-1 text-[10px] font-bold rounded-lg">EDIT</button>
                    <a href="manage_contacts.php?delete=<?= $row['id'] ?>" class="bg-red-600/20 text-red-400 px-3 py-1 text-[10px] font-bold rounded-lg">DEL</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        let isBangla = false;
        const bnD = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        
        function updateClock() {
            const now = new Date();
            let d = now.getDate(), m = now.getMonth() + 1, y = now.getFullYear();
            let h = now.getHours(), min = now.getMinutes(), s = now.getSeconds();
            
            let timeStr = `${h.toString().padStart(2,'0')}:${min.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
            if (isBangla) {
                document.getElementById('date-part').innerText = "আজ: " + `${d}${m}${y}`.replace(/\d/g, d=>bnD[d]);
                document.getElementById('clock-part').innerText = timeStr.replace(/\d/g, d=>bnD[d]);
            } else {
                document.getElementById('date-part').innerText = now.toDateString();
                document.getElementById('clock-part').innerText = timeStr;
            }
        }
        setInterval(updateClock, 1000); updateClock();

        function toggleLang() {
            isBangla = !isBangla;
            document.getElementById('title').innerText = isBangla ? "কন্টাক্ট ম্যানেজমেন্ট" : "MANAGE CONTACTS";
            document.getElementById('submitBtn').innerText = isBangla ? "সেভ করুন" : "SAVE CONTACT";
        }
        
        function editContact(data) {
            document.getElementById('contact_id').value = data.id;
            document.getElementById('customer_name').value = data.customer_name;
            document.getElementById('mobile_number').value = data.mobile_number;
            document.getElementById('email_id').value = data.email_id;
            document.getElementById('location').value = data.location;
            document.getElementById('submitBtn').innerText = "UPDATE CONTACT";
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>
