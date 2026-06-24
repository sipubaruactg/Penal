<?php
// ডাটাবেজ কানেকশন যুক্ত করা হলো
require_once 'config/db.php';

$message = "";
$error = "";

// ১. ডাটা সেভ / এডিট / পাসওয়ার্ড আপডেট প্রসেসিং
if (isset($_POST['save_admin'])) {
    $id = $_POST['admin_id'];
    $admin_name = $_POST['admin_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    $password = $_POST['password'];

    if (!empty($id)) {
        // প্রোফাইল আপডেট করা (পাসওয়ার্ড ছাড়া)
        if (empty($password)) {
            $stmt = $conn->prepare("UPDATE system_admins SET admin_name=?, email=?, username=?, phone_number=?, role=?, status=? WHERE id=?");
            $stmt->bind_param("ssssssi", $admin_name, $email, $username, $phone_number, $role, $status, $id);
        } else {
            // পাসওয়ার্ড সহ আপডেট করা (পাসওয়ার্ড হ্যাশ করে নেওয়া হচ্ছে)
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE system_admins SET admin_name=?, email=?, username=?, phone_number=?, role=?, status=?, password_hash=? WHERE id=?");
            $stmt->bind_param("sssssssi", $admin_name, $email, $username, $phone_number, $role, $status, $password_hash, $id);
        }
        if ($stmt->execute()) {
            $message = "প্রোফাইল সফলভাবে আপডেট হয়েছে!";
        } else {
            $error = "আপডেট করতে সমস্যা হয়েছে! (ইউজারনেম/ইমেইল ডুপ্লিকেট হতে পারে)";
        }
    } else {
        // নতুন এডমিন যোগ করা (পাসওয়ার্ড আবশ্যক)
        if (empty($password)) {
            $error = "নতুন এডমিন তৈরির জন্য পাসওয়ার্ড দিতে হবে!";
        } else {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO system_admins (admin_name, email, username, phone_number, role, status, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $admin_name, $email, $username, $phone_number, $role, $status, $password_hash);
            if ($stmt->execute()) {
                $message = "নতুন এডমিন সফলভাবে যুক্ত হয়েছে!";
            } else {
                $error = "এডমিন যুক্ত করা যায়নি! (ইউজারনেম/ইমেইল ডুপ্লিকেট হতে পারে)";
            }
        }
    }
}

// ২. ডাটা ডিলিট প্রসেসিং
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM system_admins WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: edit_profile.php"); // নাম পরিবর্তন করা হয়েছে
        exit;
    }
}

// ৩. সকল এডমিনের লিস্ট তুলে আনা
$admins = $conn->query("SELECT * FROM system_admins ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <title>এডমিন প্রোফাইল ও কন্ট্রোল</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        /* মোবাইল অ্যাপ ইন্টারফেস লক */
        body {
            -webkit-touch-callout: none; 
            -webkit-user-select: none;   
            user-select: none;           
            overflow: hidden;            
            position: fixed;
            width: 100%;
            height: 100%;
        }
        input, select {
            user-select: text !important;
            -webkit-user-select: text !important;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-900 text-white font-sans flex flex-col h-screen max-w-md mx-auto border-x border-gray-800 shadow-2xl">

    <header class="bg-indigo-600 text-center py-4 shadow-md shrink-0">
        <h1 class="text-xl font-bold tracking-wide">🛡️ এডমিন প্রোফাইল ও কন্ট্রোল</h1>
        <?php if(!empty($message)): ?>
            <div class="text-xs bg-green-500 text-white mx-4 mt-2 p-1 rounded"><?= $message ?></div>
        <?php endif; ?>
        <?php if(!empty($error)): ?>
            <div class="text-xs bg-red-500 text-white mx-4 mt-2 p-1 rounded"><?= $error ?></div>
        <?php endif; ?>
    </header>

    <div class="p-4 bg-gray-800 border-b border-gray-700 shrink-0">
        <form action="edit_profile.php" method="POST" id="adminForm" class="space-y-3"> <input type="hidden" name="admin_id" id="admin_id">
            
            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="admin_name" id="admin_name" placeholder="এডমিনের নাম" required 
                       class="w-full bg-gray-700 text-sm p-2 rounded border border-gray-600 focus:outline-none focus:border-indigo-500">
                <input type="text" name="phone_number" id="phone_number" placeholder="মোবাইল নাম্বার" 
                       class="w-full bg-gray-700 text-sm p-2 rounded border border-gray-600 focus:outline-none focus:border-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <input type="email" name="email" id="email" placeholder="ইমেইল এড্রেস" required 
                       class="w-full bg-gray-700 text-sm p-2 rounded border border-gray-600 focus:outline-none focus:border-indigo-500">
                <input type="text" name="username" id="username" placeholder="ইউজারনেম (Unique)" required 
                       class="w-full bg-gray-700 text-sm p-2 rounded border border-gray-600 focus:outline-none focus:border-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <select name="role" id="role" required class="w-full bg-gray-700 text-sm p-2 rounded border border-gray-600 focus:outline-none text-gray-300">
                    <option value="Manager">Manager</option>
                    <option value="SuperAdmin">SuperAdmin</option>
                    <option value="Support">Support</option>
                </select>
                <select name="status" id="status" required class="w-full bg-gray-700 text-sm p-2 rounded border border-gray-600 focus:outline-none text-gray-300">
                    <option value="Active">Active (একটিভ)</option>
                    <option value="Inactive">Inactive (ডিএকটিভ)</option>
                    <option value="Suspended">Suspended (স্থগিত)</option>
                </select>
            </div>

            <div>
                <input type="password" name="password" id="password" placeholder="নতুন পাসওয়ার্ড (পরিবর্তন না করতে চাইলে ফাঁকা রাখুন)" 
                       class="w-full bg-gray-700 text-sm p-2 rounded border border-gray-600 focus:outline-none focus:border-indigo-500">
            </div>
            
            <button type="submit" name="save_admin" id="submitBtn" 
                    class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 rounded text-sm transition duration-200">
                এডমিন প্রোফাইল সেভ করুন
            </button>
            <button type="button" onclick="resetAppForm()" id="cancelBtn" class="w-full bg-gray-600 hidden text-xs py-1 rounded">
                বাতিল করুন
            </button>
        </form>
    </div>

    <div class="flex-1 overflow-y-auto p-4 space-y-2 no-scrollbar bg-gray-950">
        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">এডমিন ও স্টাফ তালিকা</h2>
        
        <?php if($admins->num_rows > 0): ?>
            <?php while($row = $admins->fetch_assoc()): ?>
                <div class="bg-gray-800 p-3 rounded-lg flex justify-between items-center border border-gray-700 shadow-sm">
                    <div class="space-y-0.5">
                        <div class="flex items-center space-x-1.5">
                            <span class="font-bold text-sm text-gray-200"><?= htmlspecialchars($row['admin_name']) ?></span>
                            <span class="text-[9px] px-1 bg-indigo-500/30 text-indigo-300 font-semibold rounded"><?= $row['role'] ?></span>
                            <span class="text-[9px] px-1 rounded font-bold <?= $row['status'] == 'Active' ? 'bg-green-500/20 text-green-400' : ($row['status'] == 'Inactive' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') ?>">
                                <?= $row['status'] ?>
                            </span>
                        </div>
                        <p class="text-xs text-indigo-400 font-mono">@<?= htmlspecialchars($row['username']) ?> | 📞 <?= htmlspecialchars($row['phone_number'] ?? 'N/A') ?></p>
                        <p class="text-[11px] text-gray-400">📧 <?= htmlspecialchars($row['email']) ?></p>
                    </div>
                    
                    <div class="flex space-x-1 shrink-0">
                        <button onclick="editAdmin(<?= htmlspecialchars(json_encode($row)) ?>)" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 text-xs px-2 py-1.5 rounded font-medium">
                            ✏️ এডিট
                        </button>
                        <a href="edit_profile.php?delete=<?= $row['id'] ?>"  onclick="return confirm('আপনি কি নিশ্চিতভাবে এই এডমিন ডিলিট করতে চান?')" 
                           class="bg-red-500 hover:bg-red-600 text-white text-xs px-2 py-1.5 rounded font-medium">
                            🗑️ ডিলিট
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-sm text-gray-500 pt-10">কোনো এডমিন অ্যাকাউন্ট পাওয়া যায়নি।</p>
        <?php endif; ?>
    </div>

    <script>
        function editAdmin(data) {
            document.getElementById('admin_id').value = data.id;
            document.getElementById('admin_name').value = data.admin_name;
            document.getElementById('phone_number').value = data.phone_number;
            document.getElementById('email').value = data.email;
            document.getElementById('username').value = data.username;
            document.getElementById('role').value = data.role;
            document.getElementById('status').value = data.status;
            
            document.getElementById('password').placeholder = "নতুন পাসওয়ার্ড (পরিবর্তন না করতে চাইলে ফাঁকা রাখুন)";
            
            document.getElementById('submitBtn').innerText = "প্রোফাইল আপডেট করুন";
            document.getElementById('submitBtn').classList.replace('bg-indigo-500', 'bg-yellow-500');
            document.getElementById('cancelBtn').classList.remove('hidden');
        }

        function resetAppForm() {
            document.getElementById('adminForm').reset();
            document.getElementById('admin_id').value = '';
            document.getElementById('password').placeholder = "পাসওয়ার্ড (আবশ্যক)";
            document.getElementById('submitBtn').innerText = "এডমিন প্রোফাইল সেভ করুন";
            document.getElementById('submitBtn').classList.replace('bg-yellow-500', 'bg-indigo-500');
            document.getElementById('cancelBtn').classList.add('hidden');
        }
    </script>
</body>
</html>
