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
        #date-part, #clock-part { font-weight: 900; letter-spacing: 1px; }
    </style>
</head>
<body class="flex flex-col h-screen max-w-md mx-auto border-x border-gray-800">

    <div class="bg-gray-900 px-5 py-3 flex justify-between items-center border-b border-gray-800 shrink-0">
        <div class="text-[8px] text-gray-400 font-bold uppercase tracking-widest">
            <span id="date-part"></span> <span id="clock-part" class="text-emerald-400"></span>
        </div>
        <button onclick="toggleLang()" class="bg-gray-800 text-indigo-400 px-3 py-1 rounded-lg text-[9px] font-black uppercase hover:bg-gray-700 transition-all">বাংলা / EN</button>
    </div>

    <?php @include 'admin_header.php'; ?>

    <main class="flex-1 flex flex-col p-5 space-y-4 justify-center">
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

        <a href="edit_profile.php" class="menu-btn p-6 rounded-2xl flex items-center justify-center space-x-3 shadow-lg transition-all">
            <div class="text-2xl text-blue-400">⚙️</div>
            <div id="m5" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Edit Profile</div>
        </a>

        <a href="logout.php" class="w-full py-4 bg-red-900/10 border border-red-500/20 text-red-500 text-center font-black text-[10px] rounded-2xl uppercase tracking-widest active:scale-95 transition-all">
            Logout System
        </a>
    </main>

    <script>
        let isBangla = false;
        const bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const bnMonths = ["জানুয়ারি", "ফেব্রুয়ারি", "মার্চ", "এপ্রিল", "মে", "জুন", "জুলাই", "আগস্ট", "সেপ্টেম্বর", "অক্টোবর", "নভেম্বর", "ডিসেম্বর"];
        const bnDays = ["রবিবার", "সোমবার", "মঙ্গলবার", "বুধবার", "বৃহস্পতিবার", "শুক্রবার", "শনিবার"];

        function updateDisplay() {
            const now = new Date();
            let h = now.getHours();
            let m = now.getMinutes();
            let s = now.getSeconds();
            let ampm = h >= 12 ? 'PM' : 'AM';
            let bnAmpm = h >= 12 ? 'অপরাহ্ণ' : 'পূর্বাহ্ণ';
            h = h % 12 || 12;

            if (!isBangla) {
                document.getElementById('date-part').innerText = now.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
                document.getElementById('clock-part').innerText = `${h}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')} ${ampm}`;
            } else {
                let bnDate = `${bnDays[now.getDay()]}, ${now.getDate()} ${bnMonths[now.getMonth()]}`;
                let bnTime = `${h.toString().replace(/[0-9]/g, d => bnDigits[d])}:${m.toString().padStart(2, '0').replace(/[0-9]/g, d => bnDigits[d])}:${s.toString().padStart(2, '0').replace(/[0-9]/g, d => bnDigits[d])} ${bnAmpm}`;
                document.getElementById('date-part').innerText = bnDate;
                document.getElementById('clock-part').innerText = bnTime;
            }
        }
        setInterval(updateDisplay, 1000);
        updateDisplay();

        function toggleLang() {
            isBangla = !isBangla;
            document.getElementById('m1').innerText = isBangla ? "ইউজার" : "Users";
            document.getElementById('m2').innerText = isBangla ? "কন্টাক্টস" : "Contacts";
            document.getElementById('m3').innerText = isBangla ? "এক্সপোর্ট C" : "Export C";
            document.getElementById('m4').innerText = isBangla ? "এক্সপোর্ট PPO" : "Export PPO";
            document.getElementById('m5').innerText = isBangla ? "প্রোফাইল এডিট" : "Edit Profile";
            updateDisplay();
        }
    </script>
</body>
</html>
