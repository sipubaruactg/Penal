<?php
/**
 * OTP Verification Interface
 * Version: 2.0 (Secure)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['reset_token_phone'])) {
    header("Location: forget_password.php");
    exit();
}

$error = isset($_SESSION['verify_error']) ? $_SESSION['verify_error'] : "";
unset($_SESSION['verify_error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>OTP Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { -webkit-touch-callout: none; user-select: none; overflow: hidden; position: fixed; width: 100%; height: 100%; }
        input { user-select: text !important; -webkit-user-select: text !important; }
    </style>
</head>
<body class="bg-gray-950 text-white font-sans flex flex-col h-screen max-w-md mx-auto border-x border-gray-800 justify-center px-6">

    <div class="text-center mb-8">
        <div class="w-20 h-20 bg-blue-600/10 text-blue-500 rounded-2xl border border-blue-500/20 mx-auto flex items-center justify-center text-4xl mb-3">
            🔑
        </div>
        <h1 class="text-2xl font-black uppercase tracking-widest text-blue-400">Verify OTP</h1>
        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.2em] mt-1">Enter the 6-digit code</p>
    </div>

    <div class="bg-gray-900 p-6 rounded-2xl border border-gray-800 shadow-2xl space-y-4">
        
        <?php if(!empty($error)): ?>
            <div class="text-[11px] bg-red-500/10 text-red-400 border border-red-500/20 p-3 rounded-lg text-center font-bold uppercase tracking-widest">
                ⚠️ <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="verify_process.php" method="POST" class="space-y-4">
            
            <div>
                <input type="text" name="otp_code" required placeholder="******" maxlength="6" 
                       class="w-full bg-gray-950 text-center font-mono tracking-[1em] text-2xl p-4 rounded-xl border border-gray-800 focus:border-blue-500 outline-none transition-all placeholder-gray-700">
            </div>

            <button type="submit" name="submit_verify" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-xl text-xs uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-blue-950/50">
                Verify Code
            </button>

        </form>
    </div>

    <footer class="text-center text-[9px] text-gray-700 font-bold uppercase tracking-widest mt-8">
        &copy; 2026 Secured Authentication Gateway
    </footer>

</body>
</html>
