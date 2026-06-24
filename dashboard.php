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
        body { -webkit-touch-callout: none; user-select: none; overflow: hidden; position: fixed; width: 100%; height: 100%; background-color: #030712; }
        .menu-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .glass-panel { background: rgba(30, 41, 59, 0.5); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.05); }
    </style>
</head>
<body class="flex flex-col h-screen max-w-md mx-auto border-x border-gray-800">

    <header class="bg-gray-900 py-4 px-6 flex justify-between items-center border-b border-gray-800 shrink-0">
        <div>
            <h1 id="title" class="text-lg font-black uppercase tracking-widest text-indigo-400">Dashboard</h1>
            <p id="sub" class="text-[8px] text-gray-500 font-bold uppercase tracking-[0.2em]">Core Admin Interface</p>
        </div>
        <button onclick="toggleLang()" class="bg-gray-800 text-indigo-400 px-3 py-1 rounded-lg text-[9px] font-black uppercase hover:bg-gray-700 transition-all">বাংলা / EN</button>
    </header>

    <main class="flex-1 p-4 flex flex-col justify-center">
        <div class="menu-grid">
            <a href="manage_internet_users.php" class="glass-panel p-4 rounded-2xl text-center active:scale-95 transition-all">
                <div class="text-xl mb-1">🌐</div>
                <div id="m1" class="text-[9px] font-bold uppercase">Users</div>
            </a>
            <a href="manage_contacts.php" class="glass-panel p-4 rounded-2xl text-center active:scale-95 transition-all">
                <div class="text-xl mb-1">📞</div>
                <div id="m2" class="text-[9px] font-bold uppercase">Contacts</div>
            </a>
            <a href="export_contacts.php" class="glass-panel p-4 rounded-2xl text-center active:scale-95 transition-all">
                <div class="text-xl mb-1">📥</div>
                <div id="m3" class="text-[9px] font-bold uppercase">Export C</div>
            </a>
            <a href="export_ppo.php" class="glass-panel p-4 rounded-2xl text-center active:scale-95 transition-all">
                <div class="text-xl mb-1">📤</div>
                <div id="m4" class="text-[9px] font-bold uppercase">Export PPO</div>
            </a>
            <a href="edit_profile.php" class="col-span-2 glass-panel p-4 rounded-2xl flex items-center justify-center space-x-3 active:scale-95 transition-all">
                <div class="text-lg">⚙️</div>
                <div id="m5" class="text-[9px] font-bold uppercase">Edit Profile</div>
            </a>
        </div>
    </main>

    <footer class="p-4 border-t border-gray-800 shrink-0">
        <a href="logout.php" onclick="return confirm('Logout?')" id="logoutBtn" class="block w-full py-3 bg-red-900/20 border border-red-500/20 text-red-500 text-center font-black text-[10px] rounded-xl active:scale-95 transition-all uppercase tracking-widest">
            Logout System
        </a>
    </footer>

    <script>
        let isBangla = false;
        function toggleLang() {
            isBangla = !isBangla;
            document.getElementById('title').innerText = isBangla ? "ড্যাশবোর্ড" : "Dashboard";
            document.getElementById('sub').innerText = isBangla ? "মূল অ্যাডমিন ইন্টারফেস" : "Core Admin Interface";
            document.getElementById('m1').innerText = isBangla ? "ইউজার" : "Users";
            document.getElementById('m2').innerText = isBangla ? "কন্টাক্টস" : "Contacts";
            document.getElementById('m3').innerText = isBangla ? "এক্সপোর্ট C" : "Export C";
            document.getElementById('m4').innerText = isBangla ? "এক্সপোর্ট PPO" : "Export PPO";
            document.getElementById('m5').innerText = isBangla ? "প্রোফাইল এডিট" : "Edit Profile";
            document.getElementById('logoutBtn').innerText = isBangla ? "লগআউট" : "Logout System";
        }
    </script>
</body>
</html>
