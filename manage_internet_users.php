<?php
/**
 * Internet Users Management Module
 * Version: 2.0 (Secure)
 */
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

// ১. ডাটা সেভ / এডিট প্রসেসিং
if (isset($_POST['save_user'])) {
    $id = $_POST['user_id'];
    $fifi_id = $_POST['fifi_id'];
    $user_name = $_POST['user_name'];
    $mobile_number = $_POST['mobile_number'];
    $address = $_POST['address'];
    $package_price = $_POST['package_price'];
    $status = $_POST['status'];

    if (!empty($id)) {
        $stmt = $conn->prepare("UPDATE internet_users SET fifi_id=?, user_name=?, mobile_number=?, address=?, package_price=?, status=? WHERE id=?");
        $stmt->bind_param("ssssssi", $fifi_id, $user_name, $mobile_number, $address, $package_price, $status, $id);
        if ($stmt->execute()) $message = "User updated successfully!";
    } else {
        $stmt = $conn->prepare("INSERT INTO internet_users (fifi_id, user_name, mobile_number, address, package_price, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fifi_id, $user_name, $mobile_number, $address, $package_price, $status);
        if ($stmt->execute()) $message = "New user added successfully!";
    }
}

// ২. ডাটা ডিলিট প্রসেসিং
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM internet_users WHERE id=?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: manage_internet_users.php");
    exit();
}

$users = $conn->query("SELECT * FROM internet_users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Internet Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { -webkit-touch-callout: none; user-select: none; overflow: hidden; position: fixed; width: 100%; height: 100%; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-gray-950 text-white font-sans flex flex-col h-screen max-w-md mx-auto border-x border-gray-800">

    <header class="bg-gray-900 border-b border-gray-800 py-4 px-4 text-center shrink-0">
        <h1 class="text-lg font-black uppercase tracking-widest text-purple-400">Internet Users</h1>
        <?php if($message): ?><p class="text-[10px] text-emerald-400 mt-1"><?= $message ?></p><?php endif; ?>
    </header>

    <div class="p-4 bg-gray-900/50 border-b border-gray-800 shrink-0">
        <form action="manage_internet_users.php" method="POST" id="userForm" class="space-y-2">
            <input type="hidden" name="user_id" id="user_id">
            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="fifi_id" id="fifi_id" placeholder="Fifi ID" required class="w-full bg-gray-950 text-sm p-3 rounded-lg border border-gray-800 outline-none focus:border-purple-500">
                <input type="text" name="user_name" id="user_name" placeholder="Name" required class="w-full bg-gray-950 text-sm p-3 rounded-lg border border-gray-800 outline-none focus:border-purple-500">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="mobile_number" id="mobile_number" placeholder="Mobile" required class="w-full bg-gray-950 text-sm p-3 rounded-lg border border-gray-800 outline-none focus:border-purple-500">
                <input type="number" name="package_price" id="package_price" placeholder="Price" required class="w-full bg-gray-950 text-sm p-3 rounded-lg border border-gray-800 outline-none focus:border-purple-500">
            </div>
            <input type="text" name="address" id="address" placeholder="Address" class="w-full bg-gray-950 text-sm p-3 rounded-lg border border-gray-800 outline-none focus:border-purple-500">
            <select name="status" id="status" class="w-full bg-gray-950 text-sm p-3 rounded-lg border border-gray-800 outline-none focus:border-purple-500">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
            <button type="submit" name="save_user" id="submitBtn" class="w-full bg-purple-600 text-white font-bold py-3 rounded-lg text-sm active:scale-95 transition-all">SAVE USER</button>
        </form>
    </div>

    <div class="flex-1 overflow-y-auto p-4 space-y-3 no-scrollbar bg-gray-950">
        <?php while($row = $users->fetch_assoc()): ?>
            <div class="bg-gray-900 p-4 rounded-xl border border-gray-800 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-sm text-gray-200"><?= htmlspecialchars($row['user_name']) ?></h2>
                    <p class="text-[10px] text-purple-400">ID: <?= htmlspecialchars($row['fifi_id']) ?> • ৳<?= $row['package_price'] ?></p>
                </div>
                <div class="flex space-x-2">
                    <button onclick='editUser(<?= json_encode($row) ?>)' class="bg-amber-600/20 text-amber-400 px-3 py-1 text-[10px] font-bold rounded-lg">EDIT</button>
                    <a href="manage_internet_users.php?delete=<?= $row['id'] ?>" class="bg-red-600/20 text-red-400 px-3 py-1 text-[10px] font-bold rounded-lg">DEL</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        function editUser(data) {
            document.getElementById('user_id').value = data.id;
            document.getElementById('fifi_id').value = data.fifi_id;
            document.getElementById('user_name').value = data.user_name;
            document.getElementById('mobile_number').value = data.mobile_number;
            document.getElementById('address').value = data.address;
            document.getElementById('package_price').value = data.package_price;
            document.getElementById('status').value = data.status;
            document.getElementById('submitBtn').innerText = "UPDATE USER";
        }
    </script>
</body>
</html>
