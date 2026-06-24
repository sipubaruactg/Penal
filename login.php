<?php
/**
 * Admin Login Interface
 * Version: 2.0 (Stable)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : "";
unset($_SESSION['login_error']);

$message = isset($_SESSION['login_success_msg']) ? $_SESSION['login_success_msg'] : "";
unset($_SESSION['login_success_msg']);
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Admin Login</title>
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
        input { user-select: text !important; -webkit-user-select: text !important; }
    </style>
</head>
<body class="bg-gray-950 text-white font-sans flex flex-col h-screen max-w-md mx-auto border-x border-gray-800 justify-center px-6">

    <div class="text-center mb-8">
        <div class="w-20 h-20 bg-indigo-600/10 text-indigo-500 rounded-2xl border border-indigo-500/20 mx-auto flex items-center justify-center text-4xl mb-3">
            🔐
        </div>
        <h1 class="text-2xl font-black uppercase tracking-widest text-indigo-400">Admin Login</h1>
        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.2em] mt-1">Central Authentication Gateway</p>
    </div>

    <div class="bg-gray-900 p-6 rounded-2xl border border-gray-800 shadow-2xl space-y-4">
        
        <?php if(!empty($error)): ?>
            <div class="text-[11px] bg-red-500/10 text-red-400 border border-red-500/20 p-3 rounded-lg text-center font-bold uppercase tracking-widest">
                ⚠️ <?= $error ?>
            </div>
        <?php endif; ?>
        
        <?php if(!empty($message)): ?>
            <div class="text-[11px] bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 p-3 rounded-lg text-center font-bold uppercase tracking-widest">
                ✅ <?= $message ?>
            </div>
        <?php endif; ?>

        <form action="login_process.php" method="POST" class="space-y-4">
            
            <div>
                <input type="text" name="login_input" required placeholder="Username / Email / WhatsApp" 
                       class="w-full bg-gray-950 text-sm p-4 rounded-xl border border-gray-800 focus:border-indigo-500 outline-none transition-all placeholder-gray-700">
            </div>

            <div>
                <input type="password" name="password" required placeholder="Password" 
                       class="w-full bg-gray-950 text-sm p-4 rounded-xl border border-gray-800 focus:border-indigo-500 outline-none transition-all placeholder-gray-700">
            </div>

            <button type="submit" name="login" 
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-xl text-xs uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-indigo-950/50">
                Login Now
            </button>

            <div class="text-center pt-2">
                <a href="forget_password.php" 
                   class="text-[10px] text-gray-500 hover:text-white font-bold uppercase tracking-widest transition-all">
                    Forgot Password?
                </a>
            </div>

        </form>
    </div>

    <footer class="text-center text-[9px] text-gray-700 font-bold uppercase tracking-widest mt-8">
        &copy; 2026 Core Admin System
    </footer>

</body>
</html>
