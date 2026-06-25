<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #030712; height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
    </style>
</head>
<body class="text-white">

    <header class="p-4">
        <a href="index.php" class="inline-block bg-gray-800 px-5 py-2 rounded-xl text-xs font-black uppercase">← BACK</a>
    </header>

    <div class="text-center py-6">
        <h1 class="text-indigo-500 font-black text-2xl uppercase tracking-widest">Contact Dashboard</h1>
    </div>

    <main class="flex-grow grid grid-cols-2 gap-4 p-6 content-start">
        <a href="view_contacts.php" class="bg-gray-900 border border-gray-800 p-6 rounded-3xl flex flex-col items-center justify-center gap-3 hover:border-indigo-600 transition-all">
            <span class="text-3xl">📞</span>
            <span class="font-black text-xs uppercase">View Contacts</span>
        </a>
        <a href="input_contact.php" class="bg-gray-900 border border-gray-800 p-6 rounded-3xl flex flex-col items-center justify-center gap-3 hover:border-indigo-600 transition-all">
            <span class="text-3xl">📁</span>
            <span class="font-black text-xs uppercase">File Import</span>
        </a>
        <a href="manual_contacts.php" class="bg-gray-900 border border-gray-800 p-6 rounded-3xl flex flex-col items-center justify-center gap-3 hover:border-indigo-600 transition-all">
            <span class="text-3xl">📝</span>
            <span class="font-black text-xs uppercase">Manual Input</span>
        </a>
        <a href="edit_contacts.php" class="bg-gray-900 border border-gray-800 p-6 rounded-3xl flex flex-col items-center justify-center gap-3 hover:border-indigo-600 transition-all">
            <span class="text-3xl">⚙️</span>
            <span class="font-black text-xs uppercase">Edit / Delete</span>
        </a>
    </main>

</body>
</html>
