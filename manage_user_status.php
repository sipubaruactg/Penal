<?php
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

// ১. ডিলিট লজিক
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM internet_users WHERE id=?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: manage_user_status.php"); exit();
}

// ২. ব্লক/আনব্লক লজিক (Active <-> Inactive)
if (isset($_GET['toggle_status'])) {
    $id = $_GET['toggle_status'];
    $current = $_GET['current'];
    $new_status = ($current == 'Active') ? 'Inactive' : 'Active';
    
    $stmt = $conn->prepare("UPDATE internet_users SET status=? WHERE id=?");
    $stmt->bind_param("si", $new_status, $id);
    $stmt->execute();
    header("Location: manage_user_status.php"); exit();
}

// সার্চ লজিক
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT * FROM internet_users WHERE full_name LIKE '%$search%' OR mikrotik_username LIKE '%$search%' ORDER BY id DESC";
$users = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User Status</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white p-4">

    <a href="manage_internet_users.php" class="bg-gray-800 px-4 py-2 rounded-lg text-xs font-black uppercase">← BACK</a>
    <h1 class="text-xl font-black text-purple-400 mt-4 mb-4 text-center">BLOCK / DELETE USERS</h1>

    <form method="GET" class="mb-6">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search user..." class="w-full bg-gray-900 p-4 rounded-xl border border-gray-700 outline-none">
    </form>

    <div class="space-y-4">
        <?php while($row = $users->fetch_assoc()): ?>
            <div class="bg-gray-900 p-4 rounded-2xl border border-gray-800 flex justify-between items-center">
                <div>
                    <h2 class="font-bold"><?= htmlspecialchars($row['full_name']) ?></h2>
                    <p class="text-xs text-purple-400"><?= htmlspecialchars($row['mikrotik_username']) ?> | 
                        <span class="<?= $row['status'] == 'Active' ? 'text-green-500' : 'text-red-500' ?> font-bold">
                            <?= $row['status'] ?>
                        </span>
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="?toggle_status=<?= $row['id'] ?>&current=<?= $row['status'] ?>" 
                       class="<?= $row['status'] == 'Active' ? 'bg-orange-600' : 'bg-green-600' ?> px-3 py-2 rounded-lg text-[10px] font-black uppercase">
                       <?= $row['status'] == 'Active' ? 'BLOCK' : 'UNBLOCK' ?>
                    </a>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" 
                       class="bg-red-600 px-3 py-2 rounded-lg text-[10px] font-black uppercase">DEL</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
