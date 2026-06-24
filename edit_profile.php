<?php
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

$admin_id = $_SESSION['admin_id'];
$message = "";
$error = "";

if (isset($_POST['update_profile'])) {
    $admin_name = trim($_POST['admin_name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $phone = trim($_POST['phone_number']);
    $password = trim($_POST['password']);

    // ভ্যালিডেশন
    if (empty($admin_name) || empty($email) || empty($username)) {
        $error = "Name, Email and Username are required!";
    } else {
        if (!empty($password)) {
            $stmt = $conn->prepare("UPDATE system_admins SET admin_name=?, email=?, username=?, phone_number=?, password_hash=? WHERE id=?");
            $stmt->bind_param("sssssi", $admin_name, $email, $username, $phone, $password, $admin_id);
        } else {
            $stmt = $conn->prepare("UPDATE system_admins SET admin_name=?, email=?, username=?, phone_number=? WHERE id=?");
            $stmt->bind_param("ssssi", $admin_name, $email, $username, $phone, $admin_id);
        }
        
        if ($stmt->execute()) {
            $message = "প্রোফাইল সফলভাবে আপডেট হয়েছে / Profile Updated Successfully!";
        } else {
            $error = "ডাটাবেজ আপডেট এরর! / Database Update Error!";
        }
    }
}

$result = $conn->query("SELECT * FROM system_admins WHERE id = $admin_id");
$admin = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { overflow: hidden; position: fixed; width: 100%; height: 100%; background-color: #030712; font-family: sans-serif; }
        input { background-color: #111827 !important; color: white !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="flex flex-col h-screen max-w-md mx-auto border-x border-gray-800">

    <header class="bg-gray-900 border-b border-gray-800 py-5 px-6 flex justify-between items-center shadow-lg">
        <h1 id="title" class="text-xl font-black uppercase tracking-widest text-indigo-400">Admin Profile</h1>
        <button onclick="toggleLang()" class="bg-gray-800 text-indigo-400 px-4 py-1.5 rounded-lg text-[10px] font-black uppercase hover:bg-gray-700 transition-all">বাংলা / EN</button>
    </header>

    <main class="flex-1 overflow-y-auto p-6 space-y-5 no-scrollbar">
        <?php if($message): ?>
            <div class="bg-green-500/10 text-green-400 border border-green-500/20 p-4 rounded-xl text-center font-bold text-[11px] uppercase tracking-widest animate-pulse"><?= $message ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="bg-red-500/10 text-red-400 border border-red-500/20 p-4 rounded-xl text-center font-bold text-[11px] uppercase tracking-widest"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div class="space-y-1.5"><label class="text-[9px] text-gray-500 uppercase font-bold tracking-widest ml-1" id="labelName">Name</label>
            <input type="text" name="admin_name" value="<?= htmlspecialchars($admin['admin_name']) ?>" class="w-full p-4 rounded-xl border border-gray-800 focus:border-indigo-500 outline-none transition-all"></div>
            
            <div class="space-y-1.5"><label class="text-[9px] text-gray-500 uppercase font-bold tracking-widest ml-1" id="labelPhone">Phone</label>
            <input type="text" name="phone_number" value="<?= htmlspecialchars($admin['phone_number']) ?>" class="w-full p-4 rounded-xl border border-gray-800 focus:border-indigo-500 outline-none transition-all"></div>
            
            <div class="space-y-1.5"><label class="text-[9px] text-gray-500 uppercase font-bold tracking-widest ml-1" id="labelEmail">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" class="w-full p-4 rounded-xl border border-gray-800 focus:border-indigo-500 outline-none transition-all"></div>
            
            <div class="space-y-1.5"><label class="text-[9px] text-gray-500 uppercase font-bold tracking-widest ml-1" id="labelUser">Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($admin['username']) ?>" class="w-full p-4 rounded-xl border border-gray-800 focus:border-indigo-500 outline-none transition-all"></div>
            
            <div class="space-y-1.5"><label class="text-[9px] text-gray-500 uppercase font-bold tracking-widest ml-1" id="labelPass">Password</label>
            <input type="text" name="password" value="<?= htmlspecialchars($admin['password_hash']) ?>" class="w-full p-4 rounded-xl border border-gray-800 focus:border-indigo-500 outline-none transition-all"></div>
            
            <button type="submit" name="update_profile" id="updateBtn" class="w-full bg-indigo-600 py-5 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 transition-all active:scale-95 shadow-lg shadow-indigo-950/50">Update Profile</button>
            <a href="index.php" id="backBtn" class="w-full block text-center bg-gray-800 py-5 rounded-xl font-black text-xs uppercase tracking-widest text-gray-400 hover:bg-gray-700 transition-all active:scale-95">Back</a>
        </form>
    </main>

    <script>
        let isBangla = false;
        function toggleLang() {
            isBangla = !isBangla;
            document.getElementById('title').innerText = isBangla ? "এডমিন প্রোফাইল" : "Admin Profile";
            document.getElementById('updateBtn').innerText = isBangla ? "প্রোফাইল আপডেট" : "Update Profile";
            document.getElementById('backBtn').innerText = isBangla ? "ফিরে যান" : "Back";
            document.getElementById('labelName').innerText = isBangla ? "নাম" : "Name";
            document.getElementById('labelPhone').innerText = isBangla ? "ফোন" : "Phone";
            document.getElementById('labelEmail').innerText = isBangla ? "ইমেইল" : "Email";
            document.getElementById('labelUser').innerText = isBangla ? "ইউজারনেম" : "Username";
            document.getElementById('labelPass').innerText = isBangla ? "পাসওয়ার্ড" : "Password";
        }
    </script>
</body>
</html>
