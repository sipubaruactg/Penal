<?php
/**
 * Contact Management Module - FINAL & COMPLETE
 */
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

// ১. ডাটা সেভ / এডিট প্রসেসিং
if (isset($_POST['save_contact'])) {
    $id = $_POST['contact_id'];
    $name = $_POST['customer_name']; $mobile = $_POST['mobile_number'];
    $email = $_POST['email_id']; $location = $_POST['location'];
    $note = $_POST['note']; $birthday = $_POST['birthday'];

    if (!empty($id)) {
        $stmt = $conn->prepare("UPDATE customers SET customer_name=?, mobile_number=?, email_id=?, location=?, note=?, birthday=? WHERE id=?");
        $stmt->bind_param("ssssssi", $name, $mobile, $email, $location, $note, $birthday, $id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO customers (customer_name, mobile_number, email_id, location, note, birthday) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $mobile, $email, $location, $note, $birthday);
        $stmt->execute();
    }
}

// ২. CSV ফাইল ইমপোর্ট (সব কলাম সাপোর্ট করবে)
if (isset($_POST['import_csv'])) {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
        $handle = fopen($_FILES['csv_file']['tmp_name'], "r");
        fgetcsv($handle, 1000, ","); 
        $stmt = $conn->prepare("INSERT INTO customers (customer_name, mobile_number, email_id, location, note, birthday) VALUES (?, ?, ?, ?, ?, ?)");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $stmt->bind_param("ssssss", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5]);
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
    <meta name="google" content="notranslate">
    <title>Contact Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { background-color: #030712; }</style>
</head>
<body class="text-white max-w-md mx-auto border-x border-gray-800 min-h-screen pb-10">

    <header class="bg-gray-900 border-b border-gray-800 p-4 flex justify-between items-center">
        <div class="text-center">
            <div id="day-part" class="text-[9px] font-black text-gray-500 uppercase tracking-widest"></div>
            <div id="date-part" class="text-[10px] font-black text-gray-400"></div>
            <div id="clock-part" class="text-[12px] font-black text-emerald-400"></div>
        </div>
        <button onclick="toggleLang()" class="bg-indigo-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase">বাংলা / EN</button>
    </header>

    <div class="p-4 border-b border-gray-800 space-y-3">
        <form action="manage_contacts.php" method="POST" class="space-y-3">
            <input type="hidden" name="contact_id" id="contact_id">
            <input type="text" name="customer_name" id="n_f" placeholder="Name" required class="w-full bg-gray-900 p-3 rounded-xl border border-gray-700">
            <input type="text" name="mobile_number" id="m_f" placeholder="Mobile" required class="w-full bg-gray-900 p-3 rounded-xl border border-gray-700">
            <input type="text" name="email_id" id="e_f" placeholder="Email" class="w-full bg-gray-900 p-3 rounded-xl border border-gray-700">
            <input type="text" name="location" id="l_f" placeholder="Location" class="w-full bg-gray-900 p-3 rounded-xl border border-gray-700">
            <input type="text" name="note" id="nt_f" placeholder="Note" class="w-full bg-gray-900 p-3 rounded-xl border border-gray-700">
            <input type="date" name="birthday" id="b_f" class="w-full bg-gray-900 p-3 rounded-xl border border-gray-700">
            <button type="submit" name="save_contact" id="s_b" class="w-full bg-blue-600 py-3 rounded-xl font-black text-sm uppercase">SAVE CONTACT</button>
        </form>
    </div>

    <div class="p-4 space-y-3">
        <?php while($row = $contacts->fetch_assoc()): ?>
            <div class="bg-gray-900 p-4 rounded-2xl border border-gray-800 space-y-1">
                <div class="flex justify-between items-center">
                    <h2 class="font-bold text-sm"><?= htmlspecialchars($row['customer_name']) ?></h2>
                    <a href="manage_contacts.php?delete=<?= $row['id'] ?>" class="text-red-400 text-[10px] font-black">DEL</a>
                </div>
                <p class="text-[10px] text-blue-400">📞 <?= htmlspecialchars($row['mobile_number']) ?></p>
                <?php if($row['email_id']): ?><p class="text-[10px] text-gray-400">📧 <?= htmlspecialchars($row['email_id']) ?></p><?php endif; ?>
                <?php if($row['location']): ?><p class="text-[10px] text-gray-400">📍 <?= htmlspecialchars($row['location']) ?></p><?php endif; ?>
                <?php if($row['note']): ?><p class="text-[10px] italic text-gray-500">📝 <?= htmlspecialchars($row['note']) ?></p><?php endif; ?>
                <?php if($row['birthday']): ?><p class="text-[10px] text-emerald-500">🎂 <?= htmlspecialchars($row['birthday']) ?></p><?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        let isBn = false;
        const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const bnDays = ["রবিবার", "সোমবার", "মঙ্গলবার", "বুধবার", "বৃহস্পতিবার", "শুক্রবার", "শনিবার"];
        const bnD = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        
        function updateClock() {
            const now = new Date(new Date().toLocaleString("en-US", {timeZone: "Asia/Dhaka"}));
            let d = now.getDate(), m = now.getMonth(), day = now.getDay(), y = now.getFullYear();
            let hr = now.getHours(), min = now.getMinutes(), sec = now.getSeconds();
            let ampm = hr >= 12 ? 'PM' : 'AM';
            hr = hr % 12 || 12;
            let timeStr = `${hr}:${min.toString().padStart(2,'0')}:${sec.toString().padStart(2,'0')} ${ampm}`;
            let dateStr = `${months[m]} ${d}, ${y}`;
            
            if (isBn) {
                document.getElementById('day-part').innerText = bnDays[day];
                document.getElementById('date-part').innerText = `${d}/${m+1}/${y}`.replace(/\d/g, d=>bnD[d]);
                document.getElementById('clock-part').innerText = timeStr.replace(/\d/g, d=>bnD[d]);
            } else {
                document.getElementById('day-part').innerText = days[day];
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
            document.getElementById('nt_f').placeholder = isBn ? "নোট" : "Note";
            document.getElementById('s_b').innerText = isBn ? "সেভ করুন" : "SAVE CONTACT";
        }
    </script>
</body>
</html>
