<?php
// সেশন শুরু করা হলো
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ডাটাবেজ কানেকশন যুক্ত করা
require_once 'config/db.php';

// পূর্ববর্তী ভেরিফিকেশন স্টেপগুলো পার হয়ে এসেছে কিনা তা কঠোরভাবে চেক করা হচ্ছে
if (!isset($_SESSION['reset_token_phone']) || !isset($_SESSION['otp_verified'])) {
    // যদি কেউ ডিরেক্ট এই পেজে আসতে চায়, তাকে প্রথম পেজে তাড়িয়ে দেবে
    header("Location: forget_password.php");
    exit;
}

$error = "";

if (isset($_POST['submit_reset'])) {
    $new_password = trim($_POST['new_password']);
    $whatsapp_number = $_SESSION['reset_token_phone'];

    // পাসওয়ার্ডের লেংথ সিকিউরিটি চেক
    if (strlen($new_password) < 6) {
        $error = "নিরাপত্তার জন্য পাসওয়ার্ড কমপক্ষে ৬ ডিজিটের হতে হবে!";
    } else {
        // নতুন পাসওয়ার্ডটি পাসওয়ার্ড হ্যাশে রূপান্তর (Secure BCRYPT)
        $password_hash = password_hash($new_password, PASSWORD_BCRYPT);

        // ডাটাবেজে নতুন পাসওয়ার্ড আপডেট এবং ব্যবহৃত ওটিপি কলামগুলো রিসেট (NULL) করা হচ্ছে
        $stmt = $conn->prepare("UPDATE system_admins SET password_hash = ?, password_reset_token = NULL, token_expiry = NULL WHERE phone_number = ?");
        $stmt->bind_param("ss", $password_hash, $whatsapp_number);
        
        if ($stmt->execute()) {
            // কাজ শেষ, তাই সমস্ত ফরগেট পাসওয়ার্ড সংক্রান্ত সেশন ধ্বংস (Clear) করা হলো
            unset($_SESSION['reset_token_phone']);
            unset($_SESSION['otp_verified']);
            
            // লগইন পেজে দেখানোর জন্য সাকসেস মেসেজ সেট করা হলো
            $_SESSION['login_success_msg'] = "পাসওয়ার্ড সফলভাবে পরিবর্তিত হয়েছে! নতুন পাসওয়ার্ড দিয়ে লগইন করুন।";
            
            // সরাসরি লগইন পেজে রিডাইরেক্ট
            header("Location: login.php");
            exit;
        } else {
            $error = "পাসওয়ার্ড আপডেট করতে ব্যর্থ হয়েছে! আবার চেষ্টা করুন।";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <title>নতুন পাসওয়ার্ড সেট করুন</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        /* মোবাইল অ্যাপের মতো ইন্টারফেস লক */
        body {
            -webkit-touch-callout: none; 
            -webkit-user-select: none;   
            user-select: none;           
            overflow: hidden;            
            position: fixed;
            width: 100%;
            height: 100%;
        }
        input {
            user-select: text !important;
            -webkit-user-select: text !important;
        }
    </style>
</head>
<body class="bg-gray-900 text-white font-sans flex flex-col h-screen max-w-md mx-auto border-x border-gray-800 shadow-2xl justify-center px-6 bg-gradient-to-b from-gray-900 via-slate-950 to-gray-900">

    <div class="text-center mb-6">
        <div class="w-16 h-16 bg-indigo-600/20 text-indigo-400 rounded-2xl border border-indigo-500/30 mx-auto flex items-center justify-center text-3xl shadow-lg mb-2">
            🔒
        </div>
        <h1 class="text-xl font-black text-gray-100">নতুন পাসওয়ার্ড</h1>
        <p class="text-xs text-gray-400 mt-1">আপনার অ্যাকাউন্টের জন্য একটি শক্তিশালী নতুন পাসওয়ার্ড দিন</p>
    </div>

    <div class="bg-slate-800/80 p-5 rounded-2xl border border-gray-700/50 shadow-xl space-y-4 backdrop-blur-sm">
        
        <?php if(!empty($error)): ?>
            <div class="text-xs bg-red-500/20 text-red-400 border border-red-500/30 p-2 rounded-lg text-center font-medium">
                ⚠️ <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="reset_password.php" method="POST" class="space-y-4">
            
            <div>
                <label class="text-xs font-bold text-gray-400 block mb-1.5 pl-1">নতুন পাসওয়ার্ড লিখুন</label>
                <input type="password" name="new_password" required placeholder="••••••••" 
                       class="w-full bg-gray-950 text-sm p-3 rounded-xl border border-gray-700 focus:outline-none focus:border-indigo-500 text-gray-100 transition duration-150 placeholder-gray-600">
            </div>

            <button type="submit" name="submit_reset" 
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 rounded-xl text-sm tracking-wider transition duration-150 transform active:scale-95 shadow-lg shadow-indigo-950/50 mt-2">
                পাসওয়ার্ড নিশ্চিত করুন
            </button>

        </form>
    </div>

    <footer class="text-center text-[10px] text-gray-600 font-medium mt-8">
        &copy; 2026 Secured Authentication Gateway
    </footer>

</body>
</html>
