<?php
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$message = "";

if (isset($_POST['update_profile'])) {
    $admin_name = $_POST['admin_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone = $_POST['phone_number'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $stmt = $conn->prepare("UPDATE system_admins SET admin_name=?, email=?, username=?, phone_number=?, password_hash=? WHERE id=?");
        $stmt->bind_param("sssssi", $admin_name, $email, $username, $phone, $password, $admin_id);
    } else {
        $stmt = $conn->prepare("UPDATE system_admins SET admin_name=?, email=?, username=?, phone_number=? WHERE id=?");
        $stmt->bind_param("ssssi", $admin_name, $email, $username, $phone, $admin_id);
    }
    
    if ($stmt->execute()) {
        $message = "প্রোফাইল সফলভাবে আপডেট হয়েছে!";
    }
}

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
        /* জুম এবং স্ক্রল সম্পূর্ণ লক */
        body { 
            overflow: hidden; 
            position: fixed; 
            width: 100%; 
            height: 100%; 
            touch-action: none; 
            -webkit-user-select: none;
            user-select: none;
        }
        input { user-select: text !important; }
    </style>
</head>
<body class="bg-gray-950 flex items-center justify-center h-screen">

    <div class="w-full max-w-sm px-6">
        <h1 class="text-2xl font-black text-indigo-500 mb-6 uppercase tracking-widest text-center">Admin Profile</h1>
        
        <?php if($message): ?>
            <div class="bg-indigo-900/30 text-indigo-300 p-2 text-[10px] mb-4 text-center rounded font-bold uppercase"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-3">
            <input type="text" name="admin_name" value="<?= htmlspecialchars($admin['admin_name']) ?>" class="w-full bg-gray-900 p-3.5 rounded-xl border border-gray-800 text-sm outline-none focus:border-indigo-500 transition-all">
            <input type="text" name="phone_number" value="<?= htmlspecialchars($admin['phone_number']) ?>" class="w-full bg-gray-900 p-3.5 rounded-xl border border-gray-800 text-sm outline-none focus:border-indigo-500 transition-all">
            <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" class="w-full bg-gray-900 p-3.5 rounded-xl border border-gray-800 text-sm outline-none focus:border-indigo-500 transition-all">
            <input type="text" name="username" value="<?= htmlspecialchars($admin['username']) ?>" class="w-full bg-gray-900 p-3.5 rounded-xl border border-gray-800 text-sm outline-none focus:border-indigo-500 transition-all">
            <input type="text" name="password" value="<?= htmlspecialchars($admin['password_hash']) ?>" class="w-full bg-gray-900 p-3.5 rounded-xl border border-gray-800 text-sm outline-none focus:border-indigo-500 transition-all">
            
            <div class="pt-4 space-y-3">
                <button type="submit" name="update_profile" class="w-full bg-indigo-600 py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 transition-all active:scale-95 shadow-lg shadow-indigo-950">Update Profile</button>
                <a href="index.php" class="w-full block text-center bg-gray-800 py-4 rounded-xl font-black text-xs uppercase tracking-widest text-gray-300 hover:bg-gray-700 transition-all active:scale-95">Back</a>
            </div>
        </form>
    </div>

</body>
</html>
