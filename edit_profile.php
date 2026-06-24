<?php
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$message = "";

// তথ্য আপডেট প্রসেস
if (isset($_POST['update_profile'])) {
    $admin_name = $_POST['admin_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];

    if (!empty($password)) {
        // নতুন পাসওয়ার্ডসহ আপডেট
        $stmt = $conn->prepare("UPDATE system_admins SET admin_name=?, email=?, username=?, phone_number=?, password_hash=? WHERE id=?");
        $stmt->bind_param("sssssi", $admin_name, $email, $username, $phone_number, $password, $admin_id);
    } else {
        // পাসওয়ার্ড ছাড়া আপডেট
        $stmt = $conn->prepare("UPDATE system_admins SET admin_name=?, email=?, username=?, phone_number=? WHERE id=?");
        $stmt->bind_param("ssssi", $admin_name, $email, $username, $phone_number, $admin_id);
    }
    
    if ($stmt->execute()) {
        $message = "সফলভাবে আপডেট হয়েছে!";
    }
}

// ডাটা লোড করা
$result = $conn->query("SELECT * FROM system_admins WHERE id = $admin_id");
$admin = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white flex justify-center p-4">
    <div class="w-full max-w-sm bg-gray-900 p-6 rounded-2xl border border-gray-800">
        <a href="index.php" class="text-indigo-400 text-xs font-bold mb-4 block">← BACK</a>
        <h1 class="text-xl font-black text-indigo-400 mb-6 uppercase">My Profile</h1>
        
        <?php if($message): ?><div class="bg-green-900/30 text-green-400 p-2 text-xs mb-4 text-center rounded"><?= $message ?></div><?php endif; ?>

        <form method="POST" class="space-y-4">
            <input type="text" name="admin_name" value="<?= htmlspecialchars($admin['admin_name']) ?>" class="w-full bg-gray-950 p-3 rounded border border-gray-700 outline-none" required>
            <input type="text" name="phone_number" value="<?= htmlspecialchars($admin['phone_number']) ?>" class="w-full bg-gray-950 p-3 rounded border border-gray-700 outline-none">
            <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" class="w-full bg-gray-950 p-3 rounded border border-gray-700 outline-none" required>
            <input type="text" name="username" value="<?= htmlspecialchars($admin['username']) ?>" class="w-full bg-gray-950 p-3 rounded border border-gray-700 outline-none" required>
            <input type="text" name="password" placeholder="New Password (optional)" class="w-full bg-gray-950 p-3 rounded border border-gray-700 outline-none">
            
            <button type="submit" name="update_profile" class="w-full bg-indigo-600 py-3 rounded font-black text-sm uppercase">Update Profile</button>
        </form>
    </div>
</body>
</html>
