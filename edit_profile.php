<?php
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$message = "";

// আপডেট প্রসেসিং
if (isset($_POST['update_profile'])) {
    $admin_name = $_POST['admin_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone = $_POST['phone_number'];
    $password = $_POST['password'];

    // পাসওয়ার্ডসহ বা পাসওয়ার্ড ছাড়া আপডেট
    if (!empty($password)) {
        $stmt = $conn->prepare("UPDATE system_admins SET admin_name=?, email=?, username=?, phone_number=?, password_hash=? WHERE id=?");
        $stmt->bind_param("sssssi", $admin_name, $email, $username, $phone, $password, $admin_id);
    } else {
        $stmt = $conn->prepare("UPDATE system_admins SET admin_name=?, email=?, username=?, phone_number=? WHERE id=?");
        $stmt->bind_param("ssssi", $admin_name, $email, $username, $phone, $admin_id);
    }
    
    if ($stmt->execute()) {
        $message = "সফলভাবে আপডেট হয়েছে!";
    }
}

// ডাটা লোড
$result = $conn->query("SELECT * FROM system_admins WHERE id = $admin_id");
$admin = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { overflow: hidden; position: fixed; width: 100%; height: 100%; }
    </style>
</head>
<body class="bg-gray-950 text-white flex flex-col h-screen max-w-md mx-auto border-x border-gray-800">

    <div class="flex-1 p-6 flex flex-col justify-center">
        <h1 class="text-2xl font-black text-indigo-400 mb-6 uppercase tracking-widest text-center">Admin Profile</h1>
        
        <?php if($message): ?><div class="bg-green-600/20 text-green-400 p-2 text-xs mb-4 text-center rounded font-bold"><?= $message ?></div><?php endif; ?>

        <form method="POST" class="space-y-4">
            <div class="space-y-1">
                <label class="text-[10px] text-gray-500 uppercase font-bold">Name</label>
                <input type="text" name="admin_name" value="<?= htmlspecialchars($admin['admin_name']) ?>" class="w-full bg-gray-900 p-3 rounded-lg border border-gray-700 outline-none focus:border-indigo-500">
            </div>
            
            <div class="space-y-1">
                <label class="text-[10px] text-gray-500 uppercase font-bold">Phone</label>
                <input type="text" name="phone_number" value="<?= htmlspecialchars($admin['phone_number']) ?>" class="w-full bg-gray-900 p-3 rounded-lg border border-gray-700 outline-none focus:border-indigo-500">
            </div>

            <div class="space-y-1">
                <label class="text-[10px] text-gray-500 uppercase font-bold">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" class="w-full bg-gray-900 p-3 rounded-lg border border-gray-700 outline-none focus:border-indigo-500">
            </div>

            <div class="space-y-1">
                <label class="text-[10px] text-gray-500 uppercase font-bold">Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($admin['username']) ?>" class="w-full bg-gray-900 p-3 rounded-lg border border-gray-700 outline-none focus:border-indigo-500">
            </div>

            <div class="space-y-1">
                <label class="text-[10px] text-gray-500 uppercase font-bold">Password</label>
                <input type="text" name="password" value="<?= htmlspecialchars($admin['password_hash']) ?>" class="w-full bg-gray-900 p-3 rounded-lg border border-gray-700 outline-none focus:border-indigo-500">
            </div>

            <div class="pt-4 space-y-3">
                <button type="submit" name="update_profile" class="w-full bg-indigo-600 py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700">Update Profile</button>
                <a href="index.php" class="w-full block text-center bg-gray-800 py-4 rounded-xl font-bold text-xs uppercase tracking-widest text-gray-400">Back</a>
            </div>
        </form>
    </div>

</body>
</html>
