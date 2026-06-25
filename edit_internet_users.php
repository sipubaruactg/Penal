<?php
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

// আপডেট লজিক
if (isset($_POST['update_user'])) {
    $id = $_POST['user_id'];
    $m_user = $_POST['mikrotik_username'];
    $f_name = $_POST['full_name'];
    $mobile = $_POST['mobile_number'];
    $address = $_POST['address'];
    $p_name = $_POST['package_name'];
    $p_price = $_POST['package_price'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE internet_users SET mikrotik_username=?, full_name=?, mobile_number=?, address=?, package_name=?, package_price=?, status=? WHERE id=?");
    $stmt->bind_param("sssssdsi", $m_user, $f_name, $mobile, $address, $p_name, $p_price, $status, $id);
    $stmt->execute();
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
    <title>Edit Internet Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white p-4">

    <a href="manage_internet_users.php" class="bg-gray-800 px-4 py-2 rounded-lg text-xs font-black uppercase">← BACK</a>
    <h1 class="text-xl font-black text-purple-400 mt-4 mb-4 text-center">EDIT USER INFO</h1>

    <form method="GET" class="mb-6">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search user to edit..." class="w-full bg-gray-900 p-4 rounded-xl border border-gray-700 outline-none">
    </form>

    <div class="space-y-4">
        <?php while($row = $users->fetch_assoc()): ?>
            <div onclick="openEditModal(<?= htmlspecialchars(json_encode($row)) ?>)" class="bg-gray-900 p-6 rounded-2xl border border-gray-800 cursor-pointer hover:border-amber-500">
                <h2 class="text-lg font-black"><?= htmlspecialchars($row['full_name']) ?></h2>
                <p class="text-purple-400 font-mono text-sm"><?= htmlspecialchars($row['mikrotik_username']) ?></p>
            </div>
        <?php endwhile; ?>
    </div>

    <div id="editModal" class="hidden fixed inset-0 bg-black/95 p-4 flex items-center justify-center">
        <form method="POST" class="bg-gray-900 p-6 rounded-3xl w-full border border-gray-700 space-y-3">
            <input type="hidden" name="user_id" id="edit_id">
            <input type="text" name="mikrotik_username" id="edit_m_user" class="w-full bg-black p-3 rounded border border-gray-700">
            <input type="text" name="full_name" id="edit_name" class="w-full bg-black p-3 rounded border border-gray-700">
            <input type="text" name="mobile_number" id="edit_mobile" class="w-full bg-black p-3 rounded border border-gray-700">
            <input type="text" name="address" id="edit_address" class="w-full bg-black p-3 rounded border border-gray-700">
            <select name="package_name" id="edit_package" class="w-full bg-black p-3 rounded border border-gray-700">
                <option value="Basic Package (5 Mbps)">Basic Package (5 Mbps)</option>
                <option value="Standard Package (10 Mbps)">Standard Package (10 Mbps)</option>
                <option value="Premium Package (20 Mbps)">Premium Package (20 Mbps)</option>
                <option value="Gaming Package (30 Mbps)">Gaming Package (30 Mbps)</option>
            </select>
            <input type="number" name="package_price" id="edit_price" class="w-full bg-black p-3 rounded border border-gray-700">
            <select name="status" id="edit_status" class="w-full bg-black p-3 rounded border border-gray-700">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
            <button type="submit" name="update_user" class="w-full bg-amber-600 py-3 rounded-xl font-black uppercase">Update Info</button>
            <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="w-full bg-gray-800 py-3 rounded-xl font-black uppercase">Cancel</button>
        </form>
    </div>

    <script>
        function openEditModal(data) {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_m_user').value = data.mikrotik_username;
            document.getElementById('edit_name').value = data.full_name;
            document.getElementById('edit_mobile').value = data.mobile_number;
            document.getElementById('edit_address').value = data.address;
            document.getElementById('edit_package').value = data.package_name;
            document.getElementById('edit_price').value = data.package_price;
            document.getElementById('edit_status').value = data.status;
            document.getElementById('editModal').classList.remove('hidden');
        }
    </script>
</body>
</html>
