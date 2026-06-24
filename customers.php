<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'config/db.php';

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
    <title>Customer & User Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { -webkit-touch-callout: none; user-select: none; overflow: hidden; position: fixed; width: 100%; height: 100%; }
    </style>
</head>
<body class="bg-gray-950 text-white font-sans flex flex-col h-screen max-w-md mx-auto border-x border-gray-800">

    <header class="bg-gray-900 border-b border-gray-800 p-6 text-center shrink-0">
        <h1 class="text-xl font-black uppercase tracking-widest text-indigo-400">Customer & User Hub</h1>
        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.2em] mt-1">Management Portal v2.0</p>
    </header>

    <div class="flex-1 flex flex-col justify-center px-5 space-y-4 bg-gray-950">
        
        <a href="manage_contacts.php" class="group block">
            <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl flex items-center space-x-4 shadow-xl transition-all duration-300 hover:border-blue-500/50 active:scale-95">
                <div class="bg-blue-500/10 p-4 rounded-xl text-2xl group-hover:bg-blue-500/20">📞</div>
                <div>
                    <h2 class="font-bold text-gray-100">Contact Management</h2>
                    <p class="text-[11px] text-gray-500 font-medium">Manage customer directory</p>
                </div>
            </div>
        </a>

        <a href="manage_internet_users.php" class="group block">
            <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl flex items-center space-x-4 shadow-xl transition-all duration-300 hover:border-purple-500/50 active:scale-95">
                <div class="bg-purple-500/10 p-4 rounded-xl text-2xl group-hover:bg-purple-500/20">🌐</div>
                <div>
                    <h2 class="font-bold text-gray-100">Internet Users</h2>
                    <p class="text-[11px] text-gray-500 font-medium">Manage IDs, Packages & Status</p>
                </div>
            </div>
        </a>

        <a href="index.php" class="block text-center mt-6 text-gray-600 hover:text-white transition-all text-xs font-bold uppercase tracking-widest">
            ← Back to Dashboard
        </a>

    </div>

    <footer class="bg-gray-900 py-4 shrink-0 border-t border-gray-800 text-center">
        <p class="text-[9px] text-gray-600 font-bold tracking-widest uppercase">
            &copy; 2026 Admin Central System
        </p>
    </footer>

</body>
</html>