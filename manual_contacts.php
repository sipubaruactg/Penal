<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { background-color: #030712; }</style>
</head>
<body class="text-white max-w-md mx-auto min-h-screen flex flex-col justify-center p-6">

    <div class="text-center mb-10">
        <h1 class="text-indigo-500 font-black text-2xl tracking-widest">DASHBOARD</h1>
        <p class="text-gray-500 text-[10px] uppercase font-bold mt-2">Manage your contacts easily</p>
    </div>
    
    <div class="space-y-4">
        <a href="view_contacts.php" class="block bg-gray-900 p-5 rounded-2xl border border-gray-800 text-center font-black hover:border-indigo-600 transition">
            📞 VIEW CONTACTS
        </a>
        <a href="input_contact.php" class="block bg-gray-900 p-5 rounded-2xl border border-gray-800 text-center font-black hover:border-indigo-600 transition">
            ➕ ADD NEW CONTACT
        </a>
        <a href="manual_contacts.php" class="block bg-gray-900 p-5 rounded-2xl border border-gray-800 text-center font-black hover:border-indigo-600 transition">
            📝 MANUAL INPUT
        </a>
        <a href="edit_contacts.php" class="block bg-gray-900 p-5 rounded-2xl border border-gray-800 text-center font-black hover:border-indigo-600 transition">
            ⚙️ EDIT / DELETE
        </a>
    </div>

    <div class="mt-10">
        <a href="index.php" class="block w-full bg-gray-800 text-center py-4 rounded-2xl font-black text-sm uppercase tracking-widest text-gray-400 hover:bg-gray-700">
            BACK TO HOME
        </a>
    </div>

</body>
</html>
