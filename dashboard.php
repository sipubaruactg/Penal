<?php
/**
 * Main Dashboard - Core Admin Interface
 * Version: 2.0 (Stable)
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'config/db.php';

// সিকিউরিটি চেক: লগইন ছাড়া ড্যাশবোর্ড দেখা যাবে না
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard | Core Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { 
            -webkit-touch-callout: none; 
            user-select: none; 
            overflow: hidden; 
            position: fixed; 
            width: 100%; 
            height: 100%; 
        }
        .glass-panel { background: rgba(30, 41, 59, 0.6); backdrop-filter: blur(8px); }
    </style>
</head>
<body class="bg-gray-950 text-white font-sans flex flex-col h-screen max-w-md mx-auto border-x border-gray-800 shadow-2xl">

    <?php include 'admin_header.php'; ?>

    <div class="bg-gray-900/50 text-center py-5 shrink-0 border-b border-gray-800">
        <h1 class="text-2xl font-black uppercase tracking-widest bg-gradient-to-r from-cyan-400 to-indigo-400 bg-clip-text text-transparent">
            Main Dashboard
        </h1>
        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.2em] mt-1">Central Management System</p>
    </div>

    <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-950">
        
        <a href="customers.php" class="group block">
            <div class="bg-gray-900 border border-gray-800 p-5 rounded-2xl flex items-center space-x-4 shadow-xl hover:border-emerald-500/50 transition-all duration-300 active:scale-95">
                <div class="bg-emerald-500/10 p-4 rounded-xl text-2xl group-hover:bg-emerald-500/20">👥</div>
                <div>
                    <h2 class="font-bold text-gray-100">Customer & User Hub</h2>
                    <p class="text-[11px] text-gray-500 font-medium">Manage network clients & data</p>
                </div>
            </div>
        </a>

        <a href="edit_profile.php" class="group block">
            <div class="bg-gray-900 border border-gray-800 p-5 rounded-2xl flex items-center space-x-4 shadow-xl hover:border-amber-500/50 transition-all duration-300 active:scale-95">
                <div class="bg-amber-500/10 p-4 rounded-xl text-2xl group-hover:bg-amber-500/20">⚙️</div>
                <div>
                    <h2 class="font-bold text-gray-100">Admin Profile & Control</h2>
                    <p class="text-[11px] text-gray-500 font-medium">Password & System Settings</p>
                </div>
            </div>
        </a>
    </div>

    <div class="p-4 bg-gray-950 border-t border-gray-800 shrink-0 space-y-3">
        
        <div class="grid grid-cols-2 gap-3">
            <a href="export_contacts.php" class="bg-blue-600/10 border border-blue-500/20 py-3 rounded-xl text-center text-[11px] font-bold text-blue-400 hover:bg-blue-600/20 transition-all active:scale-95">
                CONTACTS
            </a>
            <a href="export_ppo.php" class="bg-indigo-600/10 border border-indigo-500/20 py-3 rounded-xl text-center text-[11px] font-bold text-indigo-400 hover:bg-indigo-600/20 transition-all active:scale-95">
                PPO BACKUP
            </a>
        </div>

        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')" class="block w-full py-3 bg-red-600/10 border border-red-500/20 text-red-500 text-center font-black text-sm rounded-xl hover:bg-red-600/20 transition-all active:scale-95">
            LOGOUT SYSTEM
        </a>
        
        <p class="text-center text-[9px] text-gray-600 font-medium tracking-widest uppercase">
            &copy; 2026 Core Admin Interface
        </p>
    </div>

</body>
</html>
