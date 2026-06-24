<?php
// ডাটাবেজ কানেকশন যুক্ত করা হলো
require_once 'config/db.php';

$message = "";

// ১. ডাটা সেভ / এডিট প্রসেসিং
if (isset($_POST['save_user'])) {
    $id = $_POST['user_id'];
    $fifi_id = $_POST['fifi_id'];
    $user_name = $_POST['user_name'];
    $mobile_number = $_POST['mobile_number'];
    $address = $_POST['address'];
    $package_price = $_POST['package_price'];
    $status = $_POST['status'];

    if (!empty($id)) {
        // আপডেট করা
        $stmt = $conn->prepare("UPDATE internet_users SET fifi_id=?, user_name=?, mobile_number=?, address=?, package_price=?, status=? WHERE id=?");
        $stmt->bind_param("ssssssi", $fifi_id, $user_name, $mobile_number, $address, $package_price, $status, $id);
        if ($stmt->execute()) $message = "ইউজার সফলভাবে আপডেট হয়েছে!";
    } else {
        // নতুন ইউজার যোগ করা
        $stmt = $conn->prepare("INSERT INTO internet_users (fifi_id, user_name, mobile_number, address, package_price, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fifi_id, $user_name, $mobile_number, $address, $package_price, $status);
        if ($stmt->execute()) $message = "নতুন ইন্টারনেট ইউজার যোগ হয়েছে!";
    }
}

// ২. ডাটা ডিলিট প্রসেসিং
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM internet_users WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: manage_internet_users.php");
        exit;
    }
}

// ৩. ইউজার লিস্ট তুলে আনা
$users = $conn->query("SELECT * FROM internet_users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <title>ইন্টারনেট ইউজার ড্যাশবোর্ড</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        /* মোবাইল অ্যাপের মতো স্ক্রিন লক ও ড্রাগ বন্ধ করার CSS */
        body {
            -webkit-touch-callout: none; 
            -webkit-user-select: none;   
            user-select: none;           
            overflow: hidden;            
            position: fixed;
            width: 100%;
            height: 100%;
        }
        input, textarea, select {
            user-select: text !important;
            -webkit-user-select: text !important;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-900 text-white font-sans flex flex-col h-screen max-w-md mx-auto border-x border-gray-800 shadow-2xl">

    <header class="bg-purple-600 text-center py-4 shadow-md shrink-0">
        <h1 class="text-xl font-bold tracking-wide">🌐 ইন্টারনেট ইউজার ড্যাশবোর্ড</h1>
        <?php if(!empty($message)): ?>
            <div class="text-xs bg-green-500 text-white mx-4 mt-2 p-1 rounded"><?= $message ?></div>
        <?php endif; ?>
    </header>

    <div class="p-4 bg-gray-800 border-b border-gray-700 shrink-0">
        <form action="manage_internet_users.php" method="POST" id="userForm" class="space-y-3">
            <input type="hidden" name="user_id" id="user_id">
            
            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="fifi_id" id="fifi_id" placeholder="ফ্রিফি (Fifi) আইডি" required 
                       class="w-full bg-gray-700 text-sm p-2 rounded border border-gray-600 focus:outline-none focus:border-purple-500">
                <input type="text" name="user_name" id="user_name" placeholder="ইউজারের নাম" required 
                       class="w-full bg-gray-700 text-sm p-2 rounded border border-gray-600 focus:outline-none focus:border-purple-500">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="mobile_number" id="mobile_number" placeholder="মোবাইল নাম্বার" required 
                       class="w-full bg-gray-700 text-sm p-2 rounded border border-gray-600 focus:outline-none focus:border-purple-500">
                <input type="number" step="0.01" name="package_price" id="package_price" placeholder="প্যাকেজ মূল্য (টাকা)" required 
                       class="w-full bg-gray-700 text-sm p-2 rounded border border-gray-600 focus:outline-none focus:border-purple-500">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="address" id="address" placeholder="বিস্তারিত এড্রেস" 
                       class="w-full bg-gray-700 text-sm p-2 rounded border border-gray-600 focus:outline-none focus:border-purple-500">
                <select name="status" id="status" required
                        class="w-full bg-gray-700 text-sm p-2 rounded border border-gray-600 focus:outline-none focus:border-purple-500 text-gray-300">
                    <option value="Active">Active (একটিভ)</option>
                    <option value="Inactive">Inactive (ডিএকটিভ)</option>
                </select>
            </div>
            
            <button type="submit" name="save_user" id="submitBtn" 
                    class="w-full bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 rounded text-sm transition duration-200">
                ইউজার ডাটা সেভ করুন
            </button>
            <button type="button" onclick="resetAppForm()" id="cancelBtn" class="w-full bg-gray-600 hidden text-xs py-1 rounded">
                বাতিল করুন
            </button>
        </form>
    </div>

    <div class="flex-1 overflow-y-auto p-4 space-y-2 no-scrollbar bg-gray-950">
        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">সর্বমোট ইন্টারনেট গ্রাহক তালিকা</h2>
        
        <?php if($users->num_rows > 0): ?>
            <?php while($row = $users->fetch_assoc()): ?>
                <div class="bg-gray-800 p-3 rounded-lg flex justify-between items-center border border-gray-700 shadow-sm">
                    <div class="space-y-0.5">
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-sm text-gray-200"><?= htmlspecialchars($row['user_name']) ?></span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded-full font-bold <?= $row['status'] == 'Active' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?>">
                                <?= $row['status'] ?>
                            </span>
                        </div>
                        <p class="text-xs text-purple-400 font-mono">ID: <?= htmlspecialchars($row['fifi_id']) ?> | 📞 <?= htmlspecialchars($row['mobile_number']) ?></p>
                        <p class="text-[11px] text-gray-400">💰 প্যাকেজ: ৳<?= htmlspecialchars($row['package_price']) ?></p>
                        <?php if(!empty($row['address'])): ?>
                            <p class="text-[11px] text-gray-500">📍 <?= htmlspecialchars($row['address']) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex space-x-1 shrink-0">
                        <button onclick="editUser(<?= htmlspecialchars(json_encode($row)) ?>)" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 text-xs px-2 py-1.5 rounded font-medium">
                            ✏️ এডিট
                        </button>
                        <a href="manage_internet_users.php?delete=<?= $row['id'] ?>" 
                           onclick="return confirm('আপনি কি নিশ্চিতভাবে এই ইউজার ডিলিট করতে চান?')" 
                           class="bg-red-500 hover:bg-red-600 text-white text-xs px-2 py-1.5 rounded font-medium">
                            🗑️ ডিলিট
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-sm text-gray-500 pt-10">কোনো ইউজার পাওয়া যায়নি।</p>
        <?php endif; ?>
    </div>

    <script>
        function editUser(data) {
            document.getElementById('user_id').value = data.id;
            document.getElementById('fifi_id').value = data.fifi_id;
            document.getElementById('user_name').value = data.user_name;
            document.getElementById('mobile_number').value = data.mobile_number;
            document.getElementById('address').value = data.address;
            document.getElementById('package_price').value = data.package_price;
            document.getElementById('status').value = data.status;
            
            document.getElementById('submitBtn').innerText = "ইউজার আপডেট করুন";
            document.getElementById('submitBtn').classList.replace('bg-purple-500', 'bg-yellow-500');
            document.getElementById('cancelBtn').classList.remove('hidden');
        }

        function resetAppForm() {
            document.getElementById('userForm').reset();
            document.getElementById('user_id').value = '';
            document.getElementById('submitBtn').innerText = "ইউজার ডাটা সেভ করুন";
            document.getElementById('submitBtn').classList.replace('bg-yellow-500', 'bg-purple-500');
            document.getElementById('cancelBtn').classList.add('hidden');
        }
    </script>
</body>
</html>
