<?php
require_once 'config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

// সার্চ লজিক
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT * FROM internet_users";
if ($search != '') {
    $sql .= " WHERE full_name LIKE '%$search%' OR mikrotik_username LIKE '%$search%' OR mobile_number LIKE '%$search%'";
}
$sql .= " ORDER BY id DESC";
$users = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Internet Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white p-4">

    <a href="manage_internet_users.php" class="bg-gray-800 px-4 py-2 rounded-lg text-xs font-black uppercase">← BACK</a>

    <h1 class="text-xl font-black text-purple-400 mt-4 mb-4 text-center">ONLINE USER STATUS</h1>

    <form method="GET" class="mb-6">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by Name, ID or Mobile..." 
               class="w-full bg-gray-900 p-4 rounded-xl border border-gray-700 outline-none text-sm">
    </form>

    <div class="space-y-4">
        <?php while($row = $users->fetch_assoc()): ?>
            <div onclick="showDetails('<?= htmlspecialchars(json_encode($row)) ?>')" class="bg-gray-900 p-6 rounded-2xl border border-gray-800 cursor-pointer hover:border-purple-500 transition-all">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-black"><?= htmlspecialchars($row['full_name']) ?></h2>
                        <p class="text-purple-400 font-mono text-sm">ID: <?= htmlspecialchars($row['mikrotik_username']) ?></p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase 
                        <?= $row['status'] == 'Active' ? 'bg-green-900 text-green-400' : 'bg-red-900 text-red-400' ?>">
                        <?= $row['status'] ?>
                    </span>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <div id="detailModal" class="hidden fixed inset-0 bg-black/90 p-6 flex items-center justify-center" onclick="this.classList.add('hidden')">
        <div class="bg-gray-900 p-8 rounded-3xl w-full border border-gray-700 space-y-4" onclick="event.stopPropagation()">
            <h2 id="m_name" class="text-2xl font-black text-purple-400"></h2>
            <p id="m_id" class="font-mono text-gray-400"></p>
            <hr class="border-gray-700">
            <p id="m_mobile" class="text-lg"></p>
            <p id="m_address" class="text-lg text-gray-400"></p>
            <p id="m_package" class="text-lg font-bold"></p>
            <p id="m_price" class="text-xl font-black text-emerald-400"></p>
            
            <div class="bg-black p-4 rounded-xl border border-gray-800">
                <p class="text-[10px] text-gray-500 uppercase">Activation Date</p>
                <p id="m_act_date" class="font-bold"></p>
                <p class="text-[10px] text-gray-500 uppercase mt-2">Expiry Date</p>
                <p id="m_exp_date" class="font-bold text-red-400"></p>
            </div>

            <button onclick="document.getElementById('detailModal').classList.add('hidden')" class="w-full bg-gray-800 py-3 rounded-xl font-black uppercase">Close</button>
        </div>
    </div>

    <script>
        function showDetails(dataJson) {
            const data = JSON.parse(dataJson);
            document.getElementById('m_name').innerText = data.full_name;
            document.getElementById('m_id').innerText = "ID: " + data.mikrotik_username;
            document.getElementById('m_mobile').innerText = "📞 " + data.mobile_number;
            document.getElementById('m_address').innerText = "📍 " + data.address;
            document.getElementById('m_package').innerText = "📦 " + data.package_name;
            document.getElementById('m_price').innerText = "৳" + data.package_price;
            document.getElementById('m_act_date').innerText = data.activation_date; // তারিখ
            document.getElementById('m_exp_date').innerText = data.expiry_date;     // তারিখ
            document.getElementById('detailModal').classList.remove('hidden');
        }
    </script>
</body>
</html>
