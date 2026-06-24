<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <title>কাস্টমার ও ইউজার হাব</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        /* মোবাইল অ্যাপ ইন্টারফেস লক ও নড়াচড়া বন্ধ করার CSS */
        body {
            -webkit-touch-callout: none; 
            -webkit-user-select: none;   
            user-select: none;           
            overflow: hidden;            
            position: fixed;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body class="bg-gray-900 text-white font-sans flex flex-col h-screen max-w-md mx-auto border-x border-gray-800 shadow-2xl">

    <header class="bg-slate-800 text-center py-6 shadow-md shrink-0 border-b border-gray-700">
        <h1 class="text-2xl font-black tracking-wide text-blue-400">👥 কাস্টমার ও ইউজার হাব</h1>
        <p class="text-xs text-gray-400 mt-1">সিস্টেম ম্যানেজমেন্ট অ্যাপ মেনু</p>
    </header>

    <div class="flex-1 flex flex-col justify-center px-6 space-y-4 bg-gray-950">
        
        <a href="manage_contacts.php" class="block">
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 p-5 rounded-2xl flex items-center space-x-4 shadow-lg active:scale-95 transition transform duration-150 border border-blue-400/20">
                <div class="bg-white/10 p-3 rounded-xl text-2xl">
                    📞
                </div>
                <div>
                    <h2 class="text-lg font-bold tracking-wide">কন্টাক্ট নাম্বার</h2>
                    <p class="text-xs text-blue-100 opacity-80">গ্রাহকের নাম ও কন্টাক্ট লিস্ট ম্যানেজ করুন</p>
                </div>
            </div>
        </a>

        <a href="manage_internet_users.php" class="block">
            <div class="bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-700 hover:to-purple-600 p-5 rounded-2xl flex items-center space-x-4 shadow-lg active:scale-95 transition transform duration-150 border border-purple-400/20">
                <div class="bg-white/10 p-3 rounded-xl text-2xl">
                    🌐
                </div>
                <div>
                    <h2 class="text-lg font-bold tracking-wide">ইন্টারনেট ইউজার</h2>
                    <p class="text-xs text-purple-100 opacity-80">ফ্রিফি আইডি, প্যাকেজ ও একটিভ/ডিএকটিভ করুন</p>
                </div>
            </div>
        </a>

    </div>

    <footer class="bg-slate-800 text-center py-3 shrink-0 border-t border-gray-700 text-[11px] text-gray-500">
        &copy; 2026 Admin Central Link Panel
    </footer>

</body>
</html>
