<?php
// কানেকশন ফাইল নিশ্চিত করুন
if (!isset($conn)) {
    require_once 'config/db.php';
}

// এডমিন ডাটা ফেচিং
$admin_header_info = null;
if (isset($_SESSION['admin_id'])) {
    $stmt = $conn->prepare("SELECT admin_name, role, email, phone_number, username FROM system_admins WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['admin_id']);
    $stmt->execute();
    $admin_header_info = $stmt->get_result()->fetch_assoc();
}
?>

<header class="bg-gray-900 border-b border-gray-800 px-5 py-4 shrink-0 shadow-lg">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-indigo-600/20 rounded-full border border-indigo-500/30 flex items-center justify-center text-xl">🛡️</div>
            <div class="flex flex-col">
                <h2 class="text-[11px] font-black text-white uppercase tracking-widest">
                    <?= htmlspecialchars($admin_header_info['admin_name'] ?? 'Admin') ?>
                </h2>
                <span class="text-[9px] text-indigo-400 font-bold uppercase tracking-wider">
                    <?= htmlspecialchars($admin_header_info['username'] ?? 'User') ?>
                </span>
            </div>
        </div>

        <div class="text-right space-y-0.5">
            <div class="text-[8px] text-gray-500 uppercase tracking-widest font-bold">
                Email: <span class="text-gray-300"><?= htmlspecialchars($admin_header_info['email'] ?? 'N/A') ?></span>
            </div>
            <div class="text-[8px] text-gray-500 uppercase tracking-widest font-bold">
                Phone: <span class="text-gray-300"><?= htmlspecialchars($admin_header_info['phone_number'] ?? 'N/A') ?></span>
            </div>
        </div>
    </div>
</header>
