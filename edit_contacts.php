<?php
require_once 'config/db.php';
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

// বাল্ক ডিলিট প্রসেসিং
if (isset($_POST['bulk_delete']) && !empty($_POST['selected_contacts'])) {
    $ids = implode(',', array_map('intval', $_POST['selected_contacts']));
    $conn->query("DELETE FROM customers WHERE id IN ($ids)");
    header("Location: edit_contacts.php"); exit();
}

$contacts = $conn->query("SELECT * FROM customers ORDER BY id DESC");
?>

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

<form method="POST" action="edit_contacts.php" class="flex flex-col h-screen">
    <header class="p-4 border-b border-gray-800 flex justify-between items-center">
        <a href="manage_contacts.php" class="bg-gray-800 px-4 py-2 rounded-xl text-xs font-black">BACK</a>
        <h1 class="font-black text-sm uppercase">EDIT & DELETE</h1>
        <button type="submit" name="bulk_delete" class="bg-red-600 px-4 py-2 rounded-xl text-[10px] font-black" onclick="return confirm('Delete Selected?')">DEL ALL</button>
    </header>

    <div class="px-4 py-2">
        <button type="button" onclick="selectAll()" class="text-[9px] font-black text-indigo-400 uppercase">Select All</button>
    </div>

    <div class="flex-grow overflow-y-auto p-4 space-y-3">
        <?php while($row = $contacts->fetch_assoc()): ?>
            <div class="bg-gray-900 p-3 rounded-xl border border-gray-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="selected_contacts[]" value="<?= $row['id'] ?>" class="contact-checkbox w-5 h-5 accent-red-600">
                    <div>
                        <h2 class="text-sm font-bold"><?= htmlspecialchars($row['customer_name']) ?></h2>
                        <p class="text-[10px] text-gray-500"><?= htmlspecialchars($row['mobile_number']) ?></p>
                    </div>
                </div>
                <a href="edit_contact.php?id=<?= $row['id'] ?>" class="bg-yellow-600 px-4 py-2 rounded-lg text-[10px] font-black">EDIT</a>
            </div>
        <?php endwhile; ?>
    </div>
</form>

<script>
    function selectAll() {
        const checkboxes = document.querySelectorAll('.contact-checkbox');
        checkboxes.forEach(cb => cb.checked = true);
    }
</script>

</body>
</html>
