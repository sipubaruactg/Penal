<?php
// যদি সেশন স্টার্ট করা না থাকে, তবে সেশন স্টার্ট হবে
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// ডাটাবেজ কানেকশন (প্রয়োজন হলে ব্যবহারের জন্য)
require_once 'config/db.php';
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <title>মূল ড্যাশবোর্ড</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        /* মোবাইল অ্যাপ ইন্টারফেস লক ও নড়াচড়া বন্ধ করার CSS */
        body {
            -webkit-touch-callout: none; 
            -webkit-user-select: none;   
            user-select: none;           
            overflow: hidden;            
            position: fixed;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body class="bg-gray-900 text-white font-sans flex flex-col h-screen max-w-md mx-auto border-x border-gray-800 shadow-2xl">

    <?php include 'admin_header.php'; ?>

    <div class="bg-slate-800/60 text-center py-5 shrink-0 border-b border-gray-800">
        <h1 class="text-2xl font-black tracking-wider bg-gradient-to-r from-cyan-400 via-blue-500 to-indigo-400 bg-clip-text text-transparent">
            🚀 মেইন ড্যাশবোর্ড
        </h1>
        <p class="text-[11px] text-gray-400 mt-1 uppercase tracking-widest">Central Management Hub</p>
    </div>

    <div class="flex-1 overflow-y-auto p-6 flex flex-col justify-center space-y-4 bg-gray-950">
        
        <a href="customers.php" class="block">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 p-5 rounded-2xl flex items-center space-x-4 shadow-lg active:scale-95 transition transform duration-150 border border-emerald-400/20">
                <div class="bg-white/10 p-3 rounded-xl text-2xl">
                    👥
                </div>
                <div>
                    <h2 class="text-base font-extrabold tracking-wide text-white">কাস্টমার ও ইউজার হাব</h2>
                    <p class="text-xs text-emerald-100/80 mt-0.5">কন্টাক্ট লিস্ট এবং ইন্টারনেট গ্রাহক ম্যানেজমেন্ট</p>
                </div>
            </div>
        </a>

        <a href="edit_profile.php" class="block">
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 p-5 rounded-2xl flex items-center space-x-4 shadow-lg active:scale-95 transition transform duration-150 border border-amber-400/20">
                <div class="bg-white/10 p-3 rounded-xl text-2xl">
                    ⚙️
                </div>
                <div>
                    <h2 class="text-base font-extrabold tracking-wide text-white">এডমিন প্রোফাইল ও কন্ট্রোল</h2>
                    <p class="text-xs text-amber-100/80 mt-0.5">পাসওয়ার্ড পরিবর্তন ও এডমিন টেবিল নিয়ন্ত্রণ</p>
                </div>
            </div>
        </a>

    </div>

    <!-- বটম কন্ট্রোল এরিয়া (ব্যাকআপ মেনু ও লগআউট) -->
    <div class="p-4 bg-slate-800/40 border-t border-gray-800 shrink-0 space-y-3">
        
        <!-- নতুন যুক্ত করা কন্টাক্ট ও পিপিও ব্যাকআপ বাটন -->
        <div class="grid grid-cols-2 gap-2">
            <a href="export_contacts.php" class="block">
                <button class="w-full bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-bold py-2.5 rounded-xl text-xs tracking-wide shadow-md active:scale-95 transition transform duration-150 flex items-center justify-center space-x-1.5">
                    <span>📥</span>
                    <span>কন্টাক্ট ব্যাকআপ</span>
                </button>
            </a>
            <a href="export_ppo.php" class="block">
                <button class="w-full bg-gradient-to-r from-indigo-600 to-violet-500 hover:from-indigo-700 hover:to-violet-600 text-white font-bold py-2.5 rounded-xl text-xs tracking-wide shadow-md active:scale-95 transition transform duration-150 flex items-center justify-center space-x-1.5">
                    <span>📥</span>
                    <span>পিপিও ব্যাকআপ</span>
                </button>
            </a>
        </div>

        <!-- লগ আউট বাটন -->
        <a href="logout.php" onclick="return confirm('আপনি কি নিশ্চিতভাবে লগআউট করতে চান?')" class="block">
            <button class="w-full bg-gradient-to-r from-red-600 to-rose-500 hover:from-red-700 hover:to-rose-600 text-white font-black py-3 rounded-xl text-sm tracking-wider shadow-lg shadow-red-900/30 active:scale-95 transition transform duration-150 flex items-center justify-center space-x-2">
                <span>🚪</span>
                <span>লগ আউট করুন</span>
            </button>
        </a>
        
        <p class="text-center text-[10px] text-gray-500 font-medium">
            &copy; 2026 Core Admin Application Interface
        </p>
    </div>

</body>
</html>

