<?php
// যদি সেশন স্টার্ট করা না থাকে, তবে সেশন স্টার্ট হবে
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ডাটাবেজ কানেকশন ফাইল যুক্ত করা হলো
require_once 'config/db.php';

$error_message = "";

// ইউজার যদি অলরেডি লগইন অবস্থায় থাকে, তবে তাকে সরাসরি ড্যাশবোর্ডে পাঠিয়ে দেবে
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

// লগইন ফর্ম সাবমিট হলে প্রসেসিং করা
if (isset($_POST['login_submit'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // আপনার ডাটাবেজের এডমিন টেবিল অনুযায়ী কুয়েরি (ধরে নেওয়া হয়েছে টেবিল নাম admins বাusers)
        // এখানে একটি ডেমো কুয়েরি দেওয়া হলো, আপনার ডাটাবেজ স্ট্রাকচার অনুযায়ী পরিবর্তন করে নিতে পারেন
        $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            // পাসওয়ার্ড ম্যাচিং (যদি md5 বা plain text হয় তবে সেভাবে চেক করবেন, এখানে password_verify স্ট্যান্ডার্ড)
            if (password_verify($password, $row['password']) || $password === $row['password']) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_username'] = $username;
                
                header("Location: dashboard.php");
                exit;
            } else {
                $error_message = "ভুল পাসওয়ার্ড দিয়েছেন!";
            }
        } else {
            $error_message = "এই ইউজারনেমটি খুঁজে পাওয়া যায়নি!";
        }
    } else {
        $error_message = "সবগুলো ফিল্ড সঠিকভাবে পূরণ করুন!";
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <title>লগইন | সেন্ট্রাল এডমিন গেটওয়ে</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        /* মোবাইল অ্যাপ ইন্টারফেস লক ও স্ক্রিন বাউন্স বন্ধ করার CSS */
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
<body class="bg-gray-900 text-white font-sans flex flex-col h-screen max-w-md mx-auto border-x border-gray-800 shadow-2xl justify-between bg-gradient-to-b from-gray-950 via-slate-900 to-gray-950">

    <div class="h-1.5 w-full bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600 shrink-0"></div>

    <div class="flex-1 flex flex-col items-center justify-center px-6 text-center space-y-6">
        
        <div class="relative">
            <div class="absolute inset-0 bg-blue-500/20 blur-3xl rounded-full"></div>
            <div class="relative w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl border border-blue-400/30 flex items-center justify-center text-3xl shadow-2xl shadow-blue-950/50">
                🔒
            </div>
        </div>

        <div class="space-y-1">
            <h1 class="text-xl font-black tracking-wider bg-gradient-to-r from-white via-gray-100 to-gray-400 bg-clip-text text-transparent">
                সেন্ট্রাল এডমিন লগইন
            </h1>
            <p class="text-[10px] text-blue-400 font-black uppercase tracking-widest">
                Core Administration Hub
            </p>
            
            <?php if(!empty($error_message)): ?>
                <div class="text-[11px] bg-red-500/20 text-red-400 border border-red-500/30 mx-auto mt-3 p-2 rounded-xl max-w-xs text-center font-bold">
                    ⚠️ <?= $error_message ?>
                </div>
            <?php endif; ?>
        </div>

        <form action="index.php" method="POST" class="w-full bg-slate-900/40 border border-gray-800/80 p-5 rounded-2xl space-y-3 shadow-xl">
            <div>
                <input type="text" name="username" placeholder="ইউজারনেম / আইডি" required 
                       class="w-full bg-gray-950 text-sm p-3 rounded-xl border border-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-500 text-center font-medium">
            </div>
            <div>
                <input type="password" name="password" placeholder="পাসওয়ার্ড" required 
                       class="w-full bg-gray-950 text-sm p-3 rounded-xl border border-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-500 text-center font-medium">
            </div>
            
            <div class="pt-2">
                <button type="submit" name="login_submit" 
                        class="w-full bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 text-white font-black py-3 rounded-xl text-xs tracking-wider shadow-lg shadow-indigo-950/50 active:scale-95 transition transform duration-150 cursor-pointer">
                    অ্যাকাউন্টে প্রবেশ করুন ➔
                </button>
            </div>
        </form>

    </div>

    <div class="p-4 shrink-0 space-y-2">
        <div class="flex items-center justify-center space-x-1.5 text-[9px] text-gray-500 font-medium">
            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
            <span>সিস্টেম অলরেডি সিকিউরড অবস্থায় আছে</span>
        </div>

        <p class="text-center text-[9px] text-gray-600 font-medium">
            &copy; 2026 Core Admin Gateway. All Rights Reserved.
        </p>
    </div>

</body>
</html>