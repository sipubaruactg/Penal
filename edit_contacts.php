<?php
require_once 'config/db.php';
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

// ১. বাল্ক ডিলিট প্রসেসিং
if (isset($_POST['bulk_delete']) && !empty($_POST['selected_contacts'])) {
    $ids = implode(',', array_map('intval', $_POST['selected_contacts']));
    $conn->query("DELETE FROM customers WHERE id IN ($ids)");
}

// ২. সিঙ্গেল ডিলিট
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM customers WHERE id = $id");
}

$contacts = $conn->query("SELECT * FROM customers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { background-color: #030712; }</style>
</head>
<body class="text-white max-w-md mx-auto min-h-screen p-4">

<form method="POST" action="edit_contacts.php">
    <div class="flex justify-between items-center mb-6">
        <h1 class="font-black text-lg">Edit & Delete Panel</h1>
        <button type="submit" name="bulk_delete" class="bg-red-600 px-4 py-2 rounded-xl text-[10px] font-black" onclick="return confirm('Are you sure?')">DELETE SELECTED</button>
    </div>

    <div class="space-y-3">
        <?php while($row = $contacts->fetch_assoc()): ?>
            <div class="bg-gray-900 p-4 rounded-xl border border-gray-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="selected_contacts[]" value="<?= $row['id'] ?>" class="w-5 h-5 accent-red-600">
                    <div>
                        <h2 class="text-sm font-bold"><?= htmlspecialchars($row['customer_name']) ?></h2>
                        <p class="text-[10px] text-gray-500"><?= htmlspecialchars($row['mobile_number']) ?></p>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <a href="edit_contact.php?id=<?= $row['id'] ?>" class="bg-yellow-600 px-4 py-2 rounded-lg text-[10px] font-black">EDIT</a>
                    <a href="edit_contacts.php?delete_id=<?= $row['id'] ?>" class="bg-red-600 px-4 py-2 rounded-lg text-[10px] font-black" onclick="return confirm('Delete?')">DEL</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</form>

<div class="mt-8">
    <a href="view_contacts.php" class="block w-full bg-gray-800 text-center py-4 rounded-xl font-black text-sm uppercase">Back to View</a>
</div>

</body>
</html>
