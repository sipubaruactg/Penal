<?php
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

// সার্চ এবং লিস্ট লজিক এখানে থাকবে...
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT * FROM internet_users WHERE full_name LIKE '%$search%' OR mikrotik_username LIKE '%$search%' ORDER BY id DESC";
$users = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Internet Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white p-4">

    <a href="dashboard.php" class="bg-gray-800 px-4 py-2 rounded-lg text-[10px] font-black uppercase">← BACK TO DASHBOARD</a>

    <h1 class="text-xl font-black text-purple-400 mt-6 mb-6 text-center uppercase">Internet User Management</h1>

    <div class="grid grid-cols-2 gap-3 mb-8">
        <a href="create_internet_user.php" class="bg-purple-900/30 border border-purple-800 p-4 rounded-xl text-center font-bold text-xs">ADD NEW USER</a>
        <a href="view_internet_users.php" class="bg-gray-800 border border-gray-700 p-4 rounded-xl text-center font-bold text-xs">VIEW ALL USERS</a>
        <a href="edit_internet_users.php" class="bg-gray-800 border border-gray-700 p-4 rounded-xl text-center font-bold text-xs">EDIT USERS</a>
        <a href="manage_user_status.php" class="bg-red-900/30 border border-red-800 p-4 rounded-xl text-center font-bold text-xs">BLOCK / DELETE</a>
    </div>

    <form method="GET" class="mb-6">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search users..." class="w-full bg-gray-900 p-4 rounded-xl border border-gray-700">
    </form>

    <div class="space-y-3">
        <?php while($row = $users->fetch_assoc()): ?>
            <div class="bg-gray-900 p-4 rounded-xl border border-gray-800 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-sm"><?= htmlspecialchars($row['full_name']) ?></h2>
                    <p class="text-[10px] text-purple-400 font-mono"><?= htmlspecialchars($row['mikrotik_username']) ?></p>
                </div>
                <span class="px-2 py-1 rounded bg-black text-[9px] font-bold <?= $row['status'] == 'Active' ? 'text-green-500' : 'text-red-500' ?>">
                    <?= $row['status'] ?>
                </span>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
