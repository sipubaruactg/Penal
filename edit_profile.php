<?php
// ডাটাবেজ কানেকশন যুক্ত করা হলো
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

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
        // প্রোফাইল আপডেট করা
        if (empty($password)) {
            $stmt = $conn->prepare("UPDATE system_admins SET admin_name=?, email=?, username=?, phone_number=?, role=?, status=? WHERE id=?");
            $stmt->bind_param("ssssssi", $admin_name, $email, $username, $phone_number, $role, $status, $id);
        } else {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE system_admins SET admin_name=?, email=?, username=?, phone_number=?, role=?, status=?, password_hash=? WHERE id=?");
            $stmt->bind_param("sssssssi", $admin_name, $email, $username, $phone_number, $role, $status, $password_hash, $id);
        }
        if ($stmt->execute()) {
            $message = "প্রোফাইল সফলভাবে আপডেট হয়েছে!";
        } else {
            $error = "আপডেট করতে সমস্যা হয়েছে!";
        }
    } else {
        // নতুন এডমিন যোগ করা
        if (empty($password)) {
            $error = "নতুন এডমিনের জন্য পাসওয়ার্ড আবশ্যক!";
        } else {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO system_admins (admin_name, email, username, phone_number, role, status, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $admin_name, $email, $username, $phone_number, $role, $status, $password_hash);
            if ($stmt->execute()) {
                $message = "নতুন এডমিন সফলভাবে যুক্ত হয়েছে!";
            } else {
                $error = "এডমিন যুক্ত করা যায়নি!";
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
        header("Location: edit_profile.php");
        exit;
    }
}

// ৩. সকল এডমিনের লিস্ট
$admins = $conn->query("SELECT * FROM system_admins ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Admin Profile & Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { -webkit-touch-callout: none; user-select: none; overflow: hidden; position: fixed; width: 100%; height: 100%; }
        input, select { user-select: text !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-gray-950 text-white font-sans flex flex-col h-screen max-w-md mx-auto border-x border-gray-800">

    <header class="bg-gray-900 border-b border-gray-800 py-4 px-4 text-center shrink-0">
        <h1 class="text-lg font-black uppercase tracking-widest text-indigo-400">Admin Control</h1>
        <?php if(!empty($message)): ?><div class="text-[10px] bg-green-500/20 text-green-400 mt-2 p-1 rounded"><?= $message ?></div><?php endif; ?>
        <?php if(!empty($error)): ?><div class="text-[10px] bg-red-500/20 text-red-400 mt-2 p-1 rounded"><?= $error ?></div><?php endif; ?>
    </header>

    <div class="p-4 bg-gray-900/50 border-b border-gray-800 shrink-0">
        <form action="edit_profile.php" method="POST" id="adminForm" class="space-y-3"> 
            <input type="hidden" name="admin_id" id="admin_id">
            
            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="admin_name" id="admin_name" placeholder="Name" required class="w-full bg-gray-950 text-sm p-2 rounded border border-gray-800 focus:border-indigo-500 outline-none">
                <input type="text" name="phone_number" id="phone_number" placeholder="Phone" class="w-full bg-gray-950 text-sm p-2 rounded border border-gray-800 focus:border-indigo-500 outline-none">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <input type="email" name="email" id="email" placeholder="Email" required class="w-full bg-gray-950 text-sm p-2 rounded border border-gray-800 focus:border-indigo-500 outline-none">
                <input type="text" name="username" id="username" placeholder="Username" required class="w-full bg-gray-950 text-sm p-2 rounded border border-gray-800 focus:border-indigo-500 outline-none">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <select name="role" id="role" class="w-full bg-gray-950 text-sm p-2 rounded border border-gray-800 text-gray-400 outline-none">
                    <option value="Manager">Manager</option>
                    <option value="SuperAdmin">SuperAdmin</option>
                    <option value="Support">Support</option>
                </select>
                <select name="status" id="status" class="w-full bg-gray-950 text-sm p-2 rounded border border-gray-800 text-gray-400 outline-none">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Suspended">Suspended</option>
                </select>
            </div>

            <input type="password" name="password" id="password" placeholder="New Password (optional)" class="w-full bg-gray-950 text-sm p-2 rounded border border-gray-800 focus:border-indigo-500 outline-none">
            
            <button type="submit" name="save_admin" id="submitBtn" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-lg text-sm transition-all active:scale-95">
                SAVE PROFILE
            </button>
            <button type="button" onclick="resetAppForm()" id="cancelBtn" class="w-full bg-gray-800 text-xs py-2 rounded-lg hidden">CANCEL</button>
        </form>
    </div>

    <div class="flex-1 overflow-y-auto p-4 space-y-3 no-scrollbar bg-gray-950">
        <?php while($row = $admins->fetch_assoc()): ?>
            <div class="bg-gray-900 p-4 rounded-xl border border-gray-800 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-sm text-gray-200"><?= htmlspecialchars($row['admin_name']) ?></h2>
                    <p class="text-[10px] text-indigo-400">@<?= htmlspecialchars($row['username']) ?> • <?= $row['role'] ?></p>
                </div>
                <div class="flex space-x-2">
                    <button onclick='editAdmin(<?= json_encode($row) ?>)' class="bg-amber-600/20 text-amber-400 px-3 py-1 text-[10px] font-bold rounded-lg hover:bg-amber-600/30">EDIT</button>
                    <a href="edit_profile.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete?')" class="bg-red-600/20 text-red-400 px-3 py-1 text-[10px] font-bold rounded-lg">DEL</a>
                </div>
            </div>
        <?php endwhile; ?>
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
            document.getElementById('submitBtn').innerText = "UPDATE PROFILE";
            document.getElementById('cancelBtn').classList.remove('hidden');
        }
        function resetAppForm() {
            document.getElementById('adminForm').reset();
            document.getElementById('admin_id').value = '';
            document.getElementById('submitBtn').innerText = "SAVE PROFILE";
            document.getElementById('cancelBtn').classList.add('hidden');
        }
    </script>
</body>
</html>
