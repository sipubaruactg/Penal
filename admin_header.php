<?php
// ডাটাবেজ থেকে লেটেস্ট এডমিন তথ্য সংগ্রহ
$admin_info = null;
if (isset($_SESSION['admin_id'])) {
    $stmt = $conn->prepare("SELECT admin_name, role, email, phone, last_login, status FROM system_admins WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['admin_id']);
    $stmt->execute();
    $admin_info = $stmt->get_result()->fetch_assoc();
}
?>

<header class="bg-gray-900 border-b border-gray-800 px-5 py-4 shrink-0 shadow-lg">
    <div class="flex items-center justify-between">
        
        <div class="flex items-center space-x-3">
            <div class="relative">
                <div class="w-12 h-12 bg-indigo-600/20 rounded-full border border-indigo-500/30 flex items-center justify-center text-xl">
                    🛡️
                </div>
                <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-emerald-500 border-2 border-gray-900"></span>
            </div>
            
            <div class="flex flex-col">
                <h2 class="text-[11px] font-black text-white uppercase tracking-widest">
                    <?= htmlspecialchars($admin_info['admin_name'] ?? 'Admin') ?>
                </h2>
                <span class="text-[9px] text-indigo-400 font-bold uppercase tracking-wider">
                    <?= htmlspecialchars($admin_info['role'] ?? 'Role') ?>
                </span>
            </div>
        </div>

        <div class="text-right space-y-1">
            <div class="text-[8px] text-gray-400 uppercase tracking-widest font-bold">
                Email: <span class="text-gray-200"><?= htmlspecialchars($admin_info['email'] ?? 'N/A') ?></span>
            </div>
            <div class="text-[8px] text-gray-400 uppercase tracking-widest font-bold">
                Phone: <span class="text-gray-200"><?= htmlspecialchars($admin_info['phone'] ?? 'N/A') ?></span>
            </div>
            <div class="text-[8px] text-gray-400 uppercase tracking-widest font-bold">
                Status: <span class="text-emerald-500"><?= htmlspecialchars($admin_info['status'] ?? 'Active') ?></span>
            </div>
        </div>
    </div>
</header>
