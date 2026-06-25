<?php
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

$message = "";

if (isset($_POST['save_user'])) {
    $m_user = $_POST['mikrotik_username'];
    $f_name = $_POST['full_name'];
    $mobile = $_POST['mobile_number'];
    $address = $_POST['address'];
    $p_name = $_POST['package_name'];
    $p_price = $_POST['package_price'];
    $status = $_POST['status'];
    $act_date = $_POST['activation_date']; // নতুন
    $exp_date = $_POST['expiry_date'];     // নতুন

    $stmt = $conn->prepare("INSERT INTO internet_users (mikrotik_username, full_name, mobile_number, address, package_name, package_price, status, activation_date, expiry_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssdsss", $m_user, $f_name, $mobile, $address, $p_name, $p_price, $status, $act_date, $exp_date);
    
    if ($stmt->execute()) {
        $message = "New user created successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Internet User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white p-4">
    
    <a href="manage_internet_users.php" class="bg-gray-800 px-4 py-2 rounded-lg text-xs font-black uppercase">← BACK</a>
    
    <h1 class="text-xl font-black text-purple-400 mt-4 mb-4 text-center">CREATE INTERNET USER</h1>

    <?php if($message): ?>
        <div class="bg-emerald-900 text-emerald-400 p-3 rounded-lg text-center mb-4 text-sm font-bold"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="bg-gray-900 p-4 rounded-xl space-y-3">
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="text-[10px] text-gray-400">Activation Date</label>
                <input type="date" name="activation_date" required class="w-full bg-black p-3 rounded border border-gray-700">
            </div>
            <div>
                <label class="text-[10px] text-gray-400">Expiry Date</label>
                <input type="date" name="expiry_date" required class="w-full bg-black p-3 rounded border border-gray-700">
            </div>
        </div>

        <input type="text" name="mikrotik_username" placeholder="Mikrotik Username (Real ID)" required class="w-full bg-black p-3 rounded border border-gray-700">
        <input type="text" name="full_name" placeholder="Full Name" required class="w-full bg-black p-3 rounded border border-gray-700">
        <input type="text" name="mobile_number" placeholder="Mobile Number" required class="w-full bg-black p-3 rounded border border-gray-700">
        <input type="text" name="address" placeholder="Physical Address" class="w-full bg-black p-3 rounded border border-gray-700">
        
        <select name="package_name" class="w-full bg-black p-3 rounded border border-gray-700">
            <option value="Basic Package (5 Mbps)">Basic Package (5 Mbps)</option>
            <option value="Standard Package (10 Mbps)">Standard Package (10 Mbps)</option>
            <option value="Premium Package (20 Mbps)">Premium Package (20 Mbps)</option>
            <option value="Gaming Package (30 Mbps)">Gaming Package (30 Mbps)</option>
        </select>
        
        <input type="number" name="package_price" placeholder="Package Price" required class="w-full bg-black p-3 rounded border border-gray-700">
        <select name="status" class="w-full bg-black p-3 rounded border border-gray-700">
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>
        
        <button type="submit" name="save_user" class="w-full bg-purple-600 py-3 font-black rounded uppercase">Save New User</button>
    </form>
</body>
</html>
