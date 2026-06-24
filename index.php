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
        body { overflow: hidden; position: fixed; width: 100%; height: 100%; background-color: #030712; font-family: sans-serif; }
        .grid-container { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .menu-btn { background: linear-gradient(145deg, #111827, #0f172a); border: 1px solid #374151; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5); }
        .menu-btn:active { transform: scale(0.96); border-color: #4f46e5; }
        #date-part, #clock-part { font-weight: 900; letter-spacing: 1px; }
    </style>
</head>
<body class="flex flex-col h-screen max-w-md mx-auto border-x border-gray-800 shadow-2xl">

    <div class="bg-gray-900/80 px-5 py-4 flex justify-between items-center border-b border-gray-700 shrink-0">
        <div class="text-[9px] text-gray-300 font-bold uppercase tracking-widest">
            <span id="date-part"></span> <span id="clock-part" class="text-indigo-400"></span>
        </div>
        <button onclick="toggleLang()" class="bg-indigo-600/20 border border-indigo-500/50 text-indigo-300 px-4 py-1 rounded-full text-[10px] font-black uppercase hover:bg-indigo-600 transition-all">বাংলা / EN</button>
    </div>

    <?php include 'admin_header.php'; ?>

    <main class="flex-1 flex flex-col p-6 space-y-5 justify-center">
        <div class="grid-container">
            <a href="manage_internet_users.php" class="menu-btn p-6 rounded-3xl text-center transition-all">
                <div class="text-4xl mb-3">🌐</div>
                <div id="m1" class="text-[11px] font-black text-gray-100 uppercase tracking-widest">Users</div>
            </a>
            <a href="manage_contacts.php" class="menu-btn p-6 rounded-3xl text-center transition-all">
                <div class="text-4xl mb-3">📞</div>
                <div id="m2" class="text-[11px] font-black text-gray-100 uppercase tracking-widest">Contacts</div>
            </a>
            <a href="export_contacts.php" class="menu-btn p-6 rounded-3xl text-center transition-all">
                <div class="text-4xl mb-3">📥</div>
                <div id="m3" class="text-[11px] font-black text-gray-100 uppercase tracking-widest">Export C</div>
            </a>
            <a href="export_ppo.php" class="menu-btn p-6 rounded-3xl text-center transition-all">
                <div class="text-4xl mb-3">📤</div>
                <div id="m4" class="text-[11px] font-black text-gray-100 uppercase tracking-widest">Export PPO</div>
            </a>
        </div>

        <a href="edit_profile.php" class="menu-btn p-5 rounded-3xl flex items-center justify-center space-x-4 transition-all">
            <div class="text-3xl">⚙️</div>
            <div id="m5" class="text-[11px] font-black text-gray-100 uppercase tracking-widest">Edit Profile</div>
        </a>

        <a href="logout.php" onclick="return confirm('Logout?')" class="w-full py-5 bg-red-950/30 border border-red-500/30 text-red-400 text-center font-black text-[11px] rounded-3xl uppercase tracking-widest hover:bg-red-900/50 transition-all active:scale-95">
            Logout System
        </a>
    </main>

    <script>
        let isBangla = false;
        const bnD = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const bM = ["জানুয়ারি", "ফেব্রুয়ারি", "মার্চ", "এপ্রিল", "মে", "জুন", "জুলাই", "আগস্ট", "সেপ্টেম্বর", "অক্টোবর", "নভেম্বর", "ডিসেম্বর"];
        const bD = ["রবিবার", "সোমবার", "মঙ্গলবার", "বুধবার", "বৃহস্পতিবার", "শুক্রবার", "শনিবার"];

        function updateDisplay() {
            const n = new Date();
            let h = n.getHours(), m = n.getMinutes(), s = n.getSeconds();
            let ampm = h >= 12 ? 'PM' : 'AM', bAmpm = h >= 12 ? 'অপরাহ্ণ' : 'পূর্বাহ্ণ';
            h = h % 12 || 12;

            if (!isBangla) {
                document.getElementById('date-part').innerText = n.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
                document.getElementById('clock-part').innerText = `${h}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')} ${ampm}`;
            } else {
                document.getElementById('date-part').innerText = `${bD[n.getDay()]}, ${n.getDate()} ${bM[n.getMonth()]}`;
                document.getElementById('clock-part').innerText = `${h.toString().replace(/[0-9]/g, d => bnD[d])}:${m.toString().padStart(2, '0').replace(/[0-9]/g, d => bnD[d])}:${s.toString().padStart(2, '0').replace(/[0-9]/g, d => bnD[d])} ${bAmpm}`;
            }
        }
        setInterval(updateDisplay, 1000); updateDisplay();

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
