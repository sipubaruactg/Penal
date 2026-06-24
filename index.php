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
    <title>Core Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #030712; font-family: sans-serif; height: 100vh; overflow: hidden; }
        .grid-container { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 20px; }
        .menu-btn { background: #111827; border: 2px solid #374151; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 140px; border-radius: 24px; transition: all 0.3s; }
        .menu-btn:active { transform: scale(0.95); border-color: #4f46e5; }
        /* ঘড়ি ও তারিখ বড় করার জন্য */
        .big-font { font-size: 18px !important; font-weight: 900; color: #ffffff; }
    </style>
</head>
<body class="flex flex-col h-screen max-w-md mx-auto border-x border-gray-800">

    <div class="bg-gray-900 px-6 py-5 flex justify-between items-center border-b border-gray-800">
        <div class="big-font tracking-widest">
            <span id="date-part"></span> <span id="clock-part" class="text-emerald-400"></span>
        </div>
        <button onclick="toggleLang()" class="bg-indigo-600 text-white px-5 py-2 rounded-xl text-[12px] font-black uppercase hover:bg-indigo-700 transition-all">বাংলা / EN</button>
    </div>

    <?php @include 'admin_header.php'; ?>

    <main class="flex-1 flex flex-col p-5 space-y-6 justify-center">
        <div class="grid-container">
            <a href="manage_internet_users.php" class="menu-btn">
                <div class="text-5xl mb-3">🌐</div>
                <div id="m1" class="text-[14px] font-black text-white uppercase tracking-wider text-center">INTERNET USERS</div>
            </a>
            <a href="manage_contacts.php" class="menu-btn">
                <div class="text-5xl mb-3">📞</div>
                <div id="m2" class="text-[14px] font-black text-white uppercase tracking-wider text-center">MANAGE CONTACTS</div>
            </a>
            <a href="export_contacts.php" class="menu-btn">
                <div class="text-5xl mb-3">📥</div>
                <div id="m3" class="text-[14px] font-black text-white uppercase tracking-wider text-center">EXPORT CONTACTS</div>
            </a>
            <a href="export_ppo.php" class="menu-btn">
                <div class="text-5xl mb-3">📤</div>
                <div id="m4" class="text-[14px] font-black text-white uppercase tracking-wider text-center">EXPORT PPO ID</div>
            </a>
        </div>

        <a href="edit_profile.php" class="menu-btn mx-5" style="height: 80px; flex-direction: row; gap: 20px;">
            <div class="text-4xl">⚙️</div>
            <div id="m5" class="text-[14px] font-black text-white uppercase tracking-wider">EDIT PROFILE</div>
        </a>

        <a href="logout.php" class="mx-5 py-6 bg-red-900/20 border border-red-500/50 text-red-500 text-center font-black text-[14px] rounded-2xl uppercase tracking-widest active:scale-95 transition-all">
            LOGOUT SYSTEM
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
                document.getElementById('date-part').innerText = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                document.getElementById('clock-part').innerText = `${h}:${m.toString().padStart(2, '0')} ${ampm}`;
            } else {
                let bnDate = `${now.getDate()} ${bnMonths[now.getMonth()]}`;
                let bnTime = `${h.toString().replace(/[0-9]/g, d => bnDigits[d])}:${m.toString().padStart(2, '0').replace(/[0-9]/g, d => bnDigits[d])} ${bnAmpm}`;
                document.getElementById('date-part').innerText = bnDate;
                document.getElementById('clock-part').innerText = bnTime;
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
