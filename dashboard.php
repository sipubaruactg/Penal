<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'config/db.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard | Core Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { overflow: hidden; position: fixed; width: 100%; height: 100%; background-color: #030712; font-family: sans-serif; }
        .grid-container { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .menu-btn { background: #111827; border: 1px solid #1f2937; }
        .menu-btn:active { transform: scale(0.95); }
    </style>
</head>
<body class="flex flex-col h-screen max-w-md mx-auto border-x border-gray-800">

    <!-- হেডার -->
    <?php include 'admin_header.php'; ?>

    <main class="flex-1 flex flex-col p-5 space-y-4 justify-center">
        <!-- গ্রিড মেনু -->
        <div class="grid-container">
            <a href="manage_internet_users.php" class="menu-btn p-6 rounded-2xl text-center shadow-lg transition-all">
                <div class="text-3xl mb-2 text-blue-500">🌐</div>
                <div id="m1" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Users</div>
            </a>
            <a href="manage_contacts.php" class="menu-btn p-6 rounded-2xl text-center shadow-lg transition-all">
                <div class="text-3xl mb-2 text-gray-500">📞</div>
                <div id="m2" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Contacts</div>
            </a>
            <a href="export_contacts.php" class="menu-btn p-6 rounded-2xl text-center shadow-lg transition-all">
                <div class="text-3xl mb-2 text-orange-500">📥</div>
                <div id="m3" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Export C</div>
            </a>
            <a href="export_ppo.php" class="menu-btn p-6 rounded-2xl text-center shadow-lg transition-all">
                <div class="text-3xl mb-2 text-orange-500">📤</div>
                <div id="m4" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Export PPO</div>
            </a>
        </div>

        <!-- ফুল উইডথ বাটন -->
        <a href="edit_profile.php" class="menu-btn p-6 rounded-2xl flex items-center justify-center space-x-3 shadow-lg transition-all">
            <div class="text-2xl text-blue-400">⚙️</div>
            <div id="m5" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Edit Profile</div>
        </a>
    </main>

    <script>
        // ল্যাঙ্গুয়েজ টগল ফাংশন
        let isBangla = false;
        function toggleLang() {
            isBangla = !isBangla;
            document.getElementById('m1').innerText = isBangla ? "ইউজার" : "Users";
            document.getElementById('m2').innerText = isBangla ? "কন্টাক্টস" : "Contacts";
            document.getElementById('m3').innerText = isBangla ? "এক্সপোর্ট C" : "Export C";
            document.getElementById('m4').innerText = isBangla ? "এক্সপোর্ট PPO" : "Export PPO";
            document.getElementById('m5').innerText = isBangla ? "প্রোফাইল এডিট" : "Edit Profile";
        }
    </script>
</body>
</html>
