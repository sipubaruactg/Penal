<?php
// আলাদা করা ডাটাবেজ কানেকশন ফাইলটি যুক্ত করা হলো
require_once 'config/db.php';

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
        if ($stmt->execute()) $message = "কন্টাক্ট সফলভাবে আপডেট হয়েছে!";
    } else {
        $stmt = $conn->prepare("INSERT INTO customers (customer_name, mobile_number, email_id, location) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $mobile, $email, $location);
        if ($stmt->execute()) $message = "নতুন কন্টাক্ট যোগ হয়েছে!";
    }
}

// --- ২. ২ ধরণের CSV ফরম্যাট অটো-ডিটেক্ট করে ইমপোর্ট প্রসেসিং ---
if (isset($_POST['import_csv'])) {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
        $file_name = $_FILES['csv_file']['tmp_name'];
        $ext = pathinfo($_FILES['csv_file']['name'], PATHINFO_EXTENSION);
        
        if (strtolower($ext) === 'csv') {
            // ফাইলের ভেতরের কন্টেন্ট রিড করা
            $file_content = file_get_contents($file_name);
            
            // ফাইলের শুরুতে কোনো লুকানো UTF-8 BOM ক্যারেক্টার থাকলে তা মুছে ফেলা
            $bom = pack('H*','EFBBBF');
            $file_content = preg_replace("/^$bom/", '', $file_content);
            
            // কমা (,) নাকি সেমিকোলন (;) ব্যবহার হয়েছে তা অটো-ডিটেক্ট করা
            $delimiter = ",";
            $first_line = strtok($file_content, "\n");
            if (strpos($first_line, ';') !== false && strpos($first_line, ',') === false) {
                $delimiter = ";";
            }
            
            // কন্টেন্টকে লাইনে লাইনে ভাগ করা
            $lines = explode("\n", $file_content);
            if (!empty($lines)) {
                
                // প্রথম লাইনের হেডার রিড করা
                $headers = str_getcsv(array_shift($lines), $delimiter);
                
                $name_idx = -1;
                $phone_idx = -1;
                $email_idx = -1;
                $location_idx = -1;

                // হেডার স্ক্যান করে কলাম চিনে নেওয়া (Case-Insensitive)
                foreach ($headers as $index => $header) {
                    $header = strtolower(trim($header, " \t\n\r\0\x0B\"'"));
                    
                    if ($name_idx === -1 && (strpos($header, 'name') !== false || strpos($header, 'नाम') !== false || strpos($header, 'নাম') !== false)) {
                        $name_idx = $index;
                    }
                    if ($phone_idx === -1 && (strpos($header, 'phone') !== false || strpos($header, 'mobile') !== false || strpos($header, 'tele') !== false || strpos($header, 'value') !== false || strpos($header, 'মোবাইল') !== false)) {
                        $phone_idx = $index;
                    }
                    if ($email_idx === -1 && (strpos($header, 'email') !== false || strpos($header, 'mail') !== false)) {
                        $email_idx = $index;
                    }
                    if ($location_idx === -1 && (strpos($header, 'address') !== false || strpos($header, 'location') !== false || strpos($header, 'city') !== false || strpos($header, 'ঠিকানা') !== false)) {
                        $location_idx = $index;
                    }
                }

                // যদি ফাইলে হেডার খুঁজে না পাওয়া যায়, তবে ব্যাকআপ সিস্টেমের প্রথম ২টি কলাম (০ এবং ১) ধরে নেওয়া হবে
                if ($name_idx === -1 || $phone_idx === -1) {
                    $name_idx = 0;
                    $phone_idx = 1;
                }

                $success_count = 0;
                $stmt = $conn->prepare("INSERT INTO customers (customer_name, mobile_number, email_id, location) VALUES (?, ?, ?, ?)");
                
                // কন্টাক্ট ডাটা লুপ চালিয়ে ডাটাবেজে ইনসার্ট করা
                foreach ($lines as $line) {
                    if (empty(trim($line))) continue;
                    
                    $data = str_getcsv($line, $delimiter);
                    
                    $name = isset($data[$name_idx]) ? trim($data[$name_idx], " \t\n\r\0\x0B\"'") : '';
                    $mobile = isset($data[$phone_idx]) ? trim($data[$phone_idx], " \t\n\r\0\x0B\"'") : '';
                    $email = ($email_idx !== -1 && isset($data[$email_idx])) ? trim($data[$email_idx], " \t\n\r\0\x0B\"'") : '';
                    $location = ($location_idx !== -1 && isset($data[$location_idx])) ? trim($data[$location_idx], " \t\n\r\0\x0B\"'") : '';
                    
                    // ফোন নাম্বার ফরম্যাটিং (স্পেস, ড্যাশ, ব্র্যাকেট পরিষ্কার করা)
                    $mobile = preg_replace('/[^\d+]/', '', $mobile);

                    if (!empty($name) && !empty($mobile)) {
                        $stmt->bind_param("ssss", $name, $mobile, $email, $location);
                        if ($stmt->execute()) {
                            $success_count++;
                        }
                    }
                }
                $message = "🎉 অটো-ডিটেকশন সফল! ব্যাকআপ ফাইল থেকে " . $success_count . " টি কন্টাক্ট যুক্ত হয়েছে।";
            } else {
                $error_message = "ফাইলের ভেতর কোনো ডাটা পাওয়া যায়নি!";
            }
        } else {
            $error_message = "অনুগ্রহ করে শুধুমাত্র একটি .csv ব্যাকআপ ফাইল আপলোড করুন!";
        }
    } else {
        $error_message = "ফাইল সিলেক্ট করা হয়নি বা আপলোডে সমস্যা হয়েছে!";
    }
}

// --- ৩. ডাটা ডিলিট প্রসেসিং ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM customers WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: manage_contacts.php");
        exit;
    }
}

// --- ৪. কন্টাক্ট লিস্ট তুলে আনা ---
$contacts = $conn->query("SELECT * FROM customers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <title>কন্টাক্ট ড্যাশবোর্ড</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body {
            -webkit-touch-callout: none; 
            -webkit-user-select: none;   
            user-select: none;           
            overflow: hidden;            
            position: fixed;
            width: 100%;
            height: 100%;
        }
        input, textarea {
            user-select: text !important;
            -webkit-user-select: text !important;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-900 text-white font-sans flex flex-col h-screen max-w-md mx-auto border-x border-gray-800 shadow-2xl">

    <header class="bg-blue-600 text-center py-4 shadow-md shrink-0">
        <h1 class="text-xl font-bold tracking-wide">📞 কন্টাক্ট ড্যাশবোর্ড</h1>
        <?php if(!empty($message)): ?>
            <div class="text-xs bg-green-500 text-white mx-4 mt-2 p-1.5 rounded text-center font-medium">✅ <?= $message ?></div>
        <?php endif; ?>
        <?php if(!empty($error_message)): ?>
            <div class="text-xs bg-red-500 text-white mx-4 mt-2 p-1.5 rounded text-center font-medium">⚠️ <?= $error_message ?></div>
        <?php endif; ?>
    </header>

    <div class="p-4 bg-gray-800 border-b border-gray-700 shrink-0 space-y-4">
        
        <form action="manage_contacts.php" method="POST" id="contactForm" class="space-y-3">
            <input type="hidden" name="contact_id" id="contact_id">
            
            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="customer_name" id="customer_name" placeholder="গ্রাহকের নাম" required 
                       class="w-full bg-gray-700 text-sm p-2 rounded border border-gray-600 focus:outline-none focus:border-blue-500">
                <input type="text" name="mobile_number" id="mobile_number" placeholder="মোবাইল নাম্বার" required 
                       class="w-full bg-gray-700 text-sm p-2 rounded border border-gray-600 focus:outline-none focus:border-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <input type="email" name="email_id" id="email_id" placeholder="ইমেইল আইডি (ঐচ্ছিক)" 
                       class="w-full bg-gray-700 text-sm p-2 rounded border border-gray-600 focus:outline-none focus:border-blue-500">
                <input type="text" name="location" id="location" placeholder="লোকেশন/ঠিকানা" 
                       class="w-full bg-gray-700 text-sm p-2 rounded border border-gray-600 focus:outline-none focus:border-blue-500">
            </div>
            
            <button type="submit" name="save_contact" id="submitBtn" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded text-sm transition duration-200">
                কন্টাক্ট সেভ করুন
            </button>
            <button type="button" onclick="resetAppForm()" id="cancelBtn" class="w-full bg-gray-600 hidden text-xs py-1 rounded">
                বাতিল করুন
            </button>
        </form>

        <div class="relative flex py-1 items-center">
            <div class="flex-grow border-t border-gray-700"></div>
            <span class="flex-shrink mx-4 text-gray-500 text-xs font-bold uppercase">অথবা ব্যাকআপ ফাইল আপলোড</span>
            <div class="flex-grow border-t border-gray-700"></div>
        </div>

        <form action="manage_contacts.php" method="POST" enctype="multipart/form-data" class="flex gap-2 items-center">
            <div class="flex-1 relative">
                <input type="file" name="csv_file" id="csv_file" accept=".csv" required
                       class="w-full text-xs text-gray-400 bg-gray-950 rounded border border-gray-700 p-1.5 focus:outline-none
                              file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-bold
                              file:bg-blue-600/20 file:text-blue-400 hover:file:bg-blue-600/30 cursor-pointer">
            </div>
            <button type="submit" name="import_csv" 
                    class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-4 py-2.5 rounded transition shadow-md">
                আপলোড
            </button>
        </form>
    </div>

    <div class="flex-1 overflow-y-auto p-4 space-y-2 no-scrollbar bg-gray-950">
        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">সর্বমোট কন্টাক্ট তালিকা</h2>
        
        <?php if($contacts->num_rows > 0): ?>
            <?php while($row = $contacts->fetch_assoc()): ?>
                <div class="bg-gray-800 p-3 rounded-lg flex justify-between items-center border border-gray-700 shadow-sm">
                    <div class="space-y-0.5">
                        <p class="font-bold text-sm text-gray-200"><?= htmlspecialchars($row['customer_name']) ?></p>
                        <p class="text-xs text-blue-400 font-mono"><?= htmlspecialchars($row['mobile_number']) ?></p>
                        <?php if(!empty($row['location'])): ?>
                            <p class="text-[11px] text-gray-400">📍 <?= htmlspecialchars($row['location']) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex space-x-2 shrink-0">
                        <button onclick="editContact(<?= htmlspecialchars(json_encode($row)) ?>)" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 text-xs px-2.5 py-1.5 rounded font-medium">
                            ✏️ এডিট
                        </button>
                        <a href="manage_contacts.php?delete=<?= $row['id'] ?>" 
                           onclick="return confirm('আপনি কি নিশ্চিতভাবে এটি ডিলিট করতে চান?')" 
                           class="bg-red-500 hover:bg-red-600 text-white text-xs px-2.5 py-1.5 rounded font-medium">
                            🗑️ ডিলিট
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-sm text-gray-500 pt-10">কোনো কন্টাক্ট পাওয়া যায়নি।</p>
        <?php endif; ?>
    </div>

    <script>
        function editContact(data) {
            document.getElementById('contact_id').value = data.id;
            document.getElementById('customer_name').value = data.customer_name;
            document.getElementById('mobile_number').value = data.mobile_number;
            document.getElementById('email_id').value = data.email_id;
            document.getElementById('location').value = data.location;
            
            document.getElementById('submitBtn').innerText = "কন্টাক্ট আপডেট করুন";
            document.getElementById('submitBtn').classList.replace('bg-blue-500', 'bg-yellow-500');
            document.getElementById('cancelBtn').classList.remove('hidden');
        }

        function resetAppForm() {
            document.getElementById('contactForm').reset();
            document.getElementById('contact_id').value = '';
            document.getElementById('submitBtn').innerText = "কন্টাক্ট সেভ করুন";
            document.getElementById('submitBtn').classList.replace('bg-yellow-500', 'bg-blue-500');
            document.getElementById('cancelBtn').classList.add('hidden');
        }
    </script>
</body>
</html>

