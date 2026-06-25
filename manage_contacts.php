<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #030712; overflow: hidden; height: 100vh; }
    </style>
</head>
<body class="text-white flex flex-col h-screen">

    <header class="p-6 text-center border-b border-gray-800">
        <h1 class="text-indigo-500 font-black text-xl uppercase tracking-widest">Contact Dashboard</h1>
    </header>

    <main class="flex-grow grid grid-cols-2 gap-4 p-4 content-center">
        <a href="view_contacts.php" class="bg-gray-900 border border-gray-800 p-4 rounded-2xl flex flex-col items-center justify-center gap-2 hover:border-indigo-600">
            <span class="text-2xl">📞</span>
            <span class="font-black text-[10px] text-center">VIEW</span>
        </a>
        <a href="input_contact.php" class="bg-gray-900 border border-gray-800 p-4 rounded-2xl flex flex-col items-center justify-center gap-2 hover:border-indigo-600">
            <span class="text-2xl">➕</span>
            <span class="font-black text-[10px] text-center">ADD</span>
        </a>
        <a href="manual_contacts.php" class="bg-gray-900 border border-gray-800 p-4 rounded-2xl flex flex-col items-center justify-center gap-2 hover:border-indigo-600">
            <span class="text-2xl">📝</span>
            <span class="font-black text-[10px] text-center">MANUAL</span>
        </a>
        <a href="edit_contacts.php" class="bg-gray-900 border border-gray-800 p-4 rounded-2xl flex flex-col items-center justify-center gap-2 hover:border-indigo-600">
            <span class="text-2xl">⚙️</span>
            <span class="font-black text-[10px] text-center">EDIT/DEL</span>
        </a>
    </main>

    <footer class="p-6 border-t border-gray-800">
        <a href="index.php" class="block w-full bg-gray-900 text-center py-4 rounded-2xl font-black text-xs uppercase tracking-widest text-gray-400">
            ← BACK
        </a>
    </footer>

</body>
</html>
