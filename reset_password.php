<?php
/**
 * Password Reset Module
 * Version: 2.0 (Secure)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/db.php';

if (!isset($_SESSION['reset_token_phone']) || !isset($_SESSION['otp_verified'])) {
    header("Location: forget_password.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reset'])) {
    $new_password = trim($_POST['new_password']);
    $whatsapp_number = $_SESSION['reset_token_phone'];

    if (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {
        $password_hash = password_hash($new_password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE system_admins SET password_hash = ?, password_reset_token = NULL, token_expiry = NULL WHERE phone_number = ?");
        $stmt->bind_param("ss", $password_hash, $whatsapp_number);
        
        if ($stmt->execute()) {
            unset($_SESSION['reset_token_phone']);
            unset($_SESSION['otp_verified']);
            $_SESSION['login_success_msg'] = "Password changed successfully! Please login.";
            header("Location: login.php");
            exit();
        } else {
            $error = "Failed to update password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Set New Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { -webkit-touch-callout: none; user-select: none; overflow: hidden; position: fixed; width: 100%; height: 100%; }
        input { user-select: text !important; -webkit-user-select: text !important; }
    </style>
</head>
<body class="bg-gray-950 text-white font-sans flex flex-col h-screen max-w-md mx-auto border-x border-gray-800 justify-center px-6">

    <div class="text-center mb-8">
        <div class="w-20 h-20 bg-indigo-600/10 text-indigo-500 rounded-2xl border border-indigo-500/20 mx-auto flex items-center justify-center text-4xl mb-3">
            🔒
        </div>
        <h1 class="text-2xl font-black uppercase tracking-widest text-indigo-400">New Password</h1>
        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.2em] mt-1">Set a strong secure password</p>
    </div>

    <div class="bg-gray-900 p-6 rounded-2xl border border-gray-800 shadow-2xl space-y-4">
        <?php if(!empty($error)): ?>
            <div class="text-[11px] bg-red-500/10 text-red-400 border border-red-500/20 p-3 rounded-lg text-center font-bold uppercase tracking-widest">
                ⚠️ <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="reset_password.php" method="POST" class="space-y-4">
            <div>
                <input type="password" name="new_password" required placeholder="New Password" 
                       class="w-full bg-gray-950 text-sm p-4 rounded-xl border border-gray-800 focus:border-indigo-500 outline-none transition-all placeholder-gray-700">
            </div>

            <button type="submit" name="submit_reset" 
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-xl text-xs uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-indigo-950/50">
                Confirm Password
            </button>
        </form>
    </div>

    <footer class="text-center text-[9px] text-gray-700 font-bold uppercase tracking-widest mt-8">
        &copy; 2026 Secured Authentication Gateway
    </footer>
</body>
</html>
