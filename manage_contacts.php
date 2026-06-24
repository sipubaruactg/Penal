<?php
/**
 * Contact Management Module - FINAL & CORRECTED VERSION
 */
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

// ১. ডাটা সেভ / এডিট প্রসেসিং
if (isset($_POST['save_contact'])) {
    $id = $_POST['contact_id'];
    $name = $_POST['customer_name']; $mobile = $_POST['mobile_number'];
    $email = $_POST['email_id']; $location = $_POST['location'];
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

// ২. CSV ফাইল ইমপোর্ট
if (isset($_POST['import_csv'])) {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
        $handle = fopen($_FILES['csv_file']['tmp_name'], "r");
        fgetcsv($handle, 1000, ","); // হেডার স্কিপ
        $stmt = $conn->prepare("INSERT INTO customers (customer_name, mobile_number, email_id, location) VALUES (?, ?, ?, ?)");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $stmt->bind_param("ssss", $data[0], $data[1], $data[2], $data[3]);
            $stmt->execute();
        }
        fclose($handle);
    }
}

// ৩. ডিলিট
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM customers WHERE id=?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: manage_contacts.php"); exit();
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
    <style>body { background-color: #030712; }</style>
</head>
<body class="text-white max-w-md mx-auto border-x border-gray-800 min-h-screen pb-10">

    <header class="bg-gray-900 border-b border-gray-800 p-4 flex justify-between items-center">
        <div class="text-center">
            <div id="date-part" class="text-[10px] font-black text-gray-400"></div>
            <div id="clock-part" class="text-[12px] font-black text-emerald-400"></div>
        </div>
        <button onclick="toggleLang()" class="bg-indigo-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase">বাংলা / EN</button>
    </header>

    <div class="p-4 border-b border-gray-800">
        <form action="manage_contacts.php" method="POST" class="space-y-3">
            <input type="hidden" name="contact_id" id="contact_id">
            <input type="text" name="customer_name" id="n_f" placeholder="Name" required class="w-full bg-gray-900 p-4 rounded-xl border border-gray-700 outline-none">
            <input type="text" name="mobile_number" id="m_f" placeholder="Mobile" required class="w-full bg-gray-900 p-4 rounded-xl border border-gray-700 outline-none">
            <input type="text" name="email_id" id="e_f" placeholder="Email" class="w-full bg-gray-900 p-4 rounded-xl border border-gray-700 outline-none">
            <input type="text" name="location" id="l_f" placeholder="Location" class="w-full bg-gray-900 p-4 rounded-xl border border-gray-700 outline-none">
            <button type="submit" name="save_contact" id="s_b" class="w-full bg-blue-600 py-4 rounded-xl font-black text-sm uppercase">SAVE CONTACT</button>
        </form>
    </div>

    <div class="p-4 border-b border-gray-800">
        <form action="manage_contacts.php" method="POST" enctype="multipart/form-data" class="flex gap-2">
            <input type="file" name="csv_file" accept=".csv" required class="w-full bg-gray-900 p-3 rounded-xl border border-gray-700 text-[10px]">
            <button type="submit" name="import_csv" class="bg-green-600 px-6 py-2 rounded-xl font-black text-[10px] uppercase">Import</button>
        </form>
    </div>

    <div class="p-4 space-y-3">
        <?php while($row = $contacts->fetch_assoc()): ?>
            <div class="bg-gray-900 p-4 rounded-2xl border border-gray-800 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-sm"><?= htmlspecialchars($row['customer_name']) ?></h2>
                    <p class="text-[10px] text-blue-400 font-mono"><?= htmlspecialchars($row['mobile_number']) ?></p>
                </div>
                <a href="manage_contacts.php?delete=<?= $row['id'] ?>" class="bg-red-600/20 text-red-400 px-4 py-2 rounded-lg text-[10px] font-black">DEL</a>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="p-4">
        <a href="index.php" class="block w-full bg-gray-800 text-center py-4 rounded-2xl font-black text-sm tracking-widest hover:bg-gray-700 transition">BACK</a>
    </div>

    <script>
        let isBn = false;
        const days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const bnD = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        
        function updateClock() {
            const now = new Date(new Date().toLocaleString("en-US", {timeZone: "Asia/Dhaka"}));
            let d = now.getDate(), m = now.getMonth(), day = now.getDay(), y = now.getFullYear();
            let hr = now.getHours(), min = now.getMinutes(), sec = now.getSeconds();
            let ampm = hr >= 12 ? 'PM' : 'AM';
            hr = hr % 12 || 12;
            
            let timeStr = `${hr}:${min.toString().padStart(2,'0')}:${sec.toString().padStart(2,'0')} ${ampm}`;
            let dateStr = `${days[day]}, ${months[m]} ${d}, ${y}`;
            
            if (isBn) {
                document.getElementById('date-part').innerText = dateStr.replace(/\d/g, d=>bnD[d]);
                document.getElementById('clock-part').innerText = timeStr.replace(/\d/g, d=>bnD[d]);
            } else {
                document.getElementById('date-part').innerText = dateStr;
                document.getElementById('clock-part').innerText = timeStr;
            }
        }
        setInterval(updateClock, 1000); updateClock();

        function toggleLang() {
            isBn = !isBn;
            document.getElementById('n_f').placeholder = isBn ? "নাম" : "Name";
            document.getElementById('m_f').placeholder = isBn ? "মোবাইল" : "Mobile";
            document.getElementById('e_f').placeholder = isBn ? "ইমেইল" : "Email";
            document.getElementById('l_f').placeholder = isBn ? "ঠিকানা" : "Location";
            document.getElementById('s_b').innerText = isBn ? "সেভ করুন" : "SAVE CONTACT";
        }
    </script>
</body>
</html>
