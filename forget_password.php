<?php
// সেশন শুরু করা হলো
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// যদি ইতিমধ্যেই লগইন করা থাকে, তবে সরাসরি ড্যাশবোর্ডে পাঠিয়ে দেবে
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

// forget_process.php থেকে কোনো এরর ব্যাক করলে তা সেশনের মাধ্যমে রিসিভ করা
$error = isset($_SESSION['forget_error']) ? $_SESSION['forget_error'] : "";
unset($_SESSION['forget_error']); // একবার দেখানোর পর সেশনটি খালি করে দেওয়া হলো
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <title>পাসওয়ার্ড রিসেট</title>
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

    <div class="text-center mb-8">
        <div class="w-20 h-20 bg-green-600/20 text-green-400 rounded-2xl border border-green-500/30 mx-auto flex items-center justify-center text-4xl shadow-lg shadow-green-950/50 mb-3">
            💬
        </div>
        <h1 class="text-2xl font-black tracking-wide text-gray-100">পাসওয়ার্ড রিসেট</h1>
        <p class="text-xs text-gray-400 mt-1">আপনার নিবন্ধিত হোয়াটসঅ্যাপ নাম্বারটি দিন</p>
    </div>

    <div class="bg-slate-800/80 p-5 rounded-2xl border border-gray-700/50 shadow-xl space-y-4 backdrop-blur-sm">
        
        <?php if(!empty($error)): ?>
            <div class="text-xs bg-red-500/20 text-red-400 border border-red-500/30 p-2 rounded-lg text-center font-medium">
                ⚠️ <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="forget_process.php" method="POST" class="space-y-4">
            
            <div>
                <label class="text-xs font-bold text-gray-400 block mb-1.5 pl-1">হোয়াটসঅ্যাপ নাম্বার</label>
                <input type="text" name="whatsapp_number" required placeholder="যেমন: 017XXXXXXXX" 
                       class="w-full bg-gray-950 text-sm p-3 rounded-xl border border-gray-700 focus:outline-none focus:border-green-500 text-gray-100 transition duration-150 placeholder-gray-600">
            </div>

            <button type="submit" name="submit_forget" 
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-black py-3 rounded-xl text-sm tracking-wider transition duration-150 transform active:scale-95 shadow-lg shadow-green-950/50 mt-2">
                হোয়াটসঅ্যাপে কোড পাঠান
            </button>

            <div class="text-center pt-1">
                <a href="login.php" class="text-xs text-gray-400 hover:text-white font-medium transition inline-block active:scale-95">
                    ← লগইন পেজে ফিরে যান
                </a>
            </div>

        </form>
    </div>

    <footer class="text-center text-[10px] text-gray-600 font-medium mt-8">
        &copy; 2026 Secured Authentication Gateway
    </footer>

</body>
</html>
