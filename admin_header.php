<?php
// যদি কোনো সেশন বা ডাটাবেজ থেকে লগইন করা এডমিনের ডাটা না থাকে, তবে ডেমো ডাটা দেখাবে
// পরবর্তীতে আপনার লগইন সেশনের $admin_name, $admin_role ভেরিয়েবল এখানে বসিয়ে দিতে পারবেন।
$current_admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : "সুপার এডমিন";
$current_admin_role = isset($_SESSION['role']) ? $_SESSION['role'] : "SuperAdmin";
?>

<header class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border-b border-indigo-500/20 px-4 py-3 shrink-0 shadow-xl">
    <div class="flex items-center justify-between">
        
        <div class="flex items-center space-x-3">
            <div class="relative">
                <div class="w-11 h-11 bg-indigo-600/30 rounded-full border-2 border-indigo-400 flex items-center justify-center text-xl shadow-inner shadow-indigo-500/50">
                    🛡️
                </div>
                <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-green-400 border-2 border-slate-900 animate-pulse"></span>
            </div>
            
            <div class="space-y-0.5">
                <h2 class="text-sm font-black tracking-wide text-gray-100 truncate max-w-[150px]">
                    <?= htmlspecialchars($current_admin_name) ?>
                </h2>
                <div class="flex items-center space-x-1.5">
                    <span class="text-[9px] px-1.5 py-0.5 bg-indigo-500/20 text-indigo-300 font-bold uppercase rounded tracking-wider border border-indigo-500/30">
                        <?= htmlspecialchars($current_admin_role) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="text-right flex flex-col items-end space-y-1">
            <div id="liveClock" class="text-xs font-mono font-bold bg-slate-950/50 px-2 py-1 rounded text-emerald-400 border border-emerald-500/10 shadow-sm">
                00:00:00
            </div>
            <div class="text-[10px] text-gray-400 font-medium tracking-tight">
                📅 <?= date('d M, Y') ?>
            </div>
        </div>

    </div>
</header>

<script>
    function updateClock() {
        const now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes();
        let seconds = now.getSeconds();
        
        // AM/PM ফরম্যাট বা ২৪ ঘণ্টা ফরম্যাট (এখানে ২৪ ঘণ্টা রাখা হলো অ্যাপ লুকের জন্য)
        hours = hours < 10 ? '0' + hours : hours;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;
        
        const timeString = hours + ':' + minutes + ':' + seconds;
        const clockElement = document.getElementById('liveClock');
        if (clockElement) {
            clockElement.textContent = timeString;
        }
    }
    // প্রতি সেকেন্ডে ঘড়ি আপডেট হবে
    setInterval(updateClock, 1000);
    updateClock(); // পেজ লোড হওয়ার সাথে সাথেই চালু হবে
</script>
