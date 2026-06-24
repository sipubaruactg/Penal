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
    <title>Core Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { overflow: hidden; position: fixed; width: 100%; height: 100%; background-color: #030712; font-family: sans-serif; }
        .grid-container { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding: 16px; }
        .menu-btn { background: #111827; border: 1px solid #1f2937; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 130px; border-radius: 20px; transition: all 0.3s; }
        .menu-btn:active { transform: scale(0.95); border-color: #4f46e5; }
        #date-part, #clock-part { font-size: 14px; font-weight: 900; letter-spacing: 1px; }
    </style>
</head>
<body class="flex flex-col h-screen max-w-md mx-auto border-x border-gray-800 shadow-2xl">

    <div class="bg-gray-900 px-6 py-4 flex justify-between items-center border-b border-gray-800 shrink-0">
        <div class="text-gray-300 font-bold uppercase tracking-widest">
            <span id="date-part"></span> <span id="clock-part" class="text-indigo-400"></span>
        </div>
        <button onclick="toggleLang()" class="bg-indigo-600/20 border border-indigo-500/50 text-indigo-300 px-4 py-1 rounded-full text-[10px] font-black uppercase hover:bg-indigo-600 transition-all">বাংলা / EN</button>
    </div>

    <?php @include 'admin_header.php'; ?>

    <main class="flex-1 p-4 overflow-y-auto">
        <div class="grid-container">
            <a href="manage_internet_users.php" class="menu-btn">
                <div class="text-4xl mb-3">🌐</div>
                <div id="m1" class="text-[11px] font-black text-gray-100 uppercase tracking-widest text-center px-2">INTERNET USERS</div>
            </a>
            <a href="manage_contacts.php" class="menu-btn">
                <div class="text-4xl mb-3">📞</div>
                <div id="m2" class="text-[11px] font-black text-gray-100 uppercase tracking-widest text-center px-2">MANAGE CONTACTS</div>
            </a>
            <a href="export_contacts.php" class="menu-btn">
                <div class="text-4xl mb-3">📥</div>
                <div id="m3" class="text-[11px] font-black text-gray-100 uppercase tracking-widest text-center px-2">EXPORT CONTACTS</div>
            </a>
            <a href="export_ppo.php" class="menu-btn">
                <div class="text-4xl mb-3">📤</div>
                <div id="m4" class="text-[11px] font-black text-gray-100 uppercase tracking-widest text-center px-2">EXPORT PPO ID</div>
            </a>
        </div>

        <div class="px-4">
            <a href="edit_profile.php" class="menu-btn w-full flex-row space-x-4 mb-4" style="height: 70px;">
                <div class="text-3xl">⚙️</div>
                <div id="m5" class="text-[11px] font-black text-gray-100 uppercase tracking-widest">EDIT PROFILE</div>
            </a>

            <a href="logout.php" class="w-full py-5 bg-red-950/30 border border-red-500/30 text-red-400 text-center font-black text-[11px] rounded-2xl uppercase tracking-widest hover:bg-red-900/50 transition-all active:scale-95">
                LOGOUT SYSTEM
            </a>
        </div>
    </main>

    <script>
        let isBangla = false;
        const bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const bnMonths = ["জানুয়ারি", "ফেব্রুয়ারি", "মার্চ", "এপ্রিল", "মে", "জুন", "জুলাই", "আগস্ট", "সেপ্টেম্বর", "অক্টোবর", "নভেম্বর", "ডিসেম্বর"];
        const bnDays = ["রবিবার", "সোমবার", "মঙ্গলবার", "বুধবার", "বৃহস্পতিবার", "শুক্রবার", "শনিবার"];

        function updateDisplay() {
            const n = new Date();
            let h = n.getHours(), m = n.getMinutes(), s = n.getSeconds();
            let ampm = h >= 12 ? 'PM' : 'AM', bAmpm = h >= 12 ? 'অপরাহ্ণ' : 'পূর্বাহ্ণ';
            h = h % 12 || 12;

            if (!isBangla) {
                document.getElementById('date-part').innerText = n.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
                document.getElementById('clock-part').innerText = `${h}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')} ${ampm}`;
            } else {
                document.getElementById('date-part').innerText = `${bnDays[n.getDay()]}, ${n.getDate()} ${bnMonths[n.getMonth()]}`;
                document.getElementById('clock-part').innerText = `${h.toString().replace(/[0-9]/g, d => bnDigits[d])}:${m.toString().padStart(2, '0').replace(/[0-9]/g, d => bnDigits[d])}:${s.toString().padStart(2, '0').replace(/[0-9]/g, d => bnDigits[d])} ${bAmpm}`;
            }
        }
        setInterval(updateDisplay, 1000); updateDisplay();

        function toggleLang() {
            isBangla = !isBangla;
            document.getElementById('m1').innerText = isBangla ? "ইন্টারনেট ইউজার" : "INTERNET USERS";
            document.getElementById('m2').innerText = isBangla ? "কন্টাক্ট ম্যানেজ" : "MANAGE CONTACTS";
            document.getElementById('m3').innerText = isBangla ? "কন্টাক্ট এক্সপোর্ট" : "EXPORT CONTACTS";
            document.getElementById('m4').innerText = isBangla ? "পি পি ও এক্সপোর্ট" : "EXPORT PPO ID";
            document.getElementById('m5').innerText = isBangla ? "প্রোফাইল এডিট" : "EDIT PROFILE";
            updateDisplay();
        }
    </script>
</body>
</html>
