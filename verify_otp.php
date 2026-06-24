<?php
// সেশন শুরু করা হলো
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// আগের স্টেপ থেকে ফোন নাম্বার সেশনে না থাকলে ফরগেট পেজে ব্যাক করাবে
if (!isset($_SESSION['reset_token_phone'])) {
    header("Location: forget_password.php");
    exit;
}

// verify_process.php থেকে কোনো এরর আসলে তা রিসিভ করা
$error = isset($_SESSION['verify_error']) ? $_SESSION['verify_error'] : "";
unset($_SESSION['verify_error']); // একবার দেখানোর পর সেশনটি খালি করে দেওয়া হলো
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <title>ওটিপি ভেরিফিকেশন</title>
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
        <div class="w-16 h-16 bg-blue-600/20 text-blue-400 rounded-2xl border border-blue-500/30 mx-auto flex items-center justify-center text-3xl shadow-lg mb-2">
            🔑
        </div>
        <h1 class="text-xl font-black text-gray-100">ওটিপি ভেরিফিকেশন</h1>
        <p class="text-xs text-gray-400 mt-1">আপনার হোয়াটসঅ্যাপে পাঠানো ৬ ডিজিটের কোডটি দিন</p>
    </div>

    <div class="bg-slate-800/80 p-5 rounded-2xl border border-gray-700/50 shadow-xl space-y-4 backdrop-blur-sm">
        
        <?php if(!empty($error)): ?>
            <div class="text-xs bg-red-500/20 text-red-400 border border-red-500/30 p-2 rounded-lg text-center font-medium">
                ⚠️ <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="verify_process.php" method="POST" class="space-y-4">
            
            <div>
                <input type="text" name="otp_code" required placeholder="******" maxlength="6" 
                       class="w-full bg-gray-950 text-center font-mono tracking-widest text-xl p-3 rounded-xl border border-gray-700 focus:outline-none focus:border-blue-500 text-blue-400 placeholder-gray-800">
            </div>

            <button type="submit" name="submit_verify" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-3 rounded-xl text-sm tracking-wider transition duration-150 transform active:scale-95 shadow-lg shadow-blue-950/50 mt-2">
                কোড ভেরিফাই করুন
            </button>

        </form>
    </div>

    <footer class="text-center text-[10px] text-gray-600 font-medium mt-8">
        &copy; 2026 Secured Authentication Gateway
    </footer>

</body>
</html>
