<?php
// নিরাপত্তার জন্য এবং এরর এড়াতে ভেরিয়েবলগুলো আগে ডিফাইন করে নিন
$admin_name = "Admin";
$admin_role = "Role";
$admin_email = "N/A";
$admin_phone = "N/A";
$admin_status = "Active";

// ডাটাবেজ কানেকশন চেক করে ডাটা আনুন
if (isset($_SESSION['admin_id']) && isset($conn)) {
    $stmt = $conn->prepare("SELECT admin_name, role, email, phone, status FROM system_admins WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $_SESSION['admin_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $admin_name = $row['admin_name'] ?? 'Admin';
            $admin_role = $row['role'] ?? 'Role';
            $admin_email = $row['email'] ?? 'N/A';
            $admin_phone = $row['phone'] ?? 'N/A';
            $admin_status = $row['status'] ?? 'Active';
        }
        $stmt->close();
    }
}
?>

<header class="bg-gray-900 border-b border-gray-800 px-5 py-4 shrink-0 shadow-lg w-full">
    <div class="flex items-center justify-between">
        
        <!-- বাম পাশে: আইকন ও নাম -->
        <div class="flex items-center space-x-3">
            <div class="relative">
                <div class="w-12 h-12 bg-indigo-600/20 rounded-full border border-indigo-500/30 flex items-center justify-center text-xl">
                    🛡️
                </div>
                <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-emerald-500 border-2 border-gray-900"></span>
            </div>
            
            <div class="flex flex-col">
                <h2 class="text-[11px] font-black text-white uppercase tracking-widest">
                    <?= htmlspecialchars($admin_name) ?>
                </h2>
                <span class="text-[9px] text-indigo-400 font-bold uppercase tracking-wider">
                    <?= htmlspecialchars($admin_role) ?>
                </span>
            </div>
        </div>

        <!-- ডান পাশে: ডিটেইলস -->
        <div class="text-right space-y-0.5">
            <div class="text-[8px] text-gray-400 uppercase tracking-widest font-bold">
                E: <span class="text-gray-200"><?= htmlspecialchars($admin_email) ?></span>
            </div>
            <div class="text-[8px] text-gray-400 uppercase tracking-widest font-bold">
                P: <span class="text-gray-200"><?= htmlspecialchars($admin_phone) ?></span>
            </div>
            <div class="text-[8px] text-gray-400 uppercase tracking-widest font-bold">
                S: <span class="text-emerald-500"><?= htmlspecialchars($admin_status) ?></span>
            </div>
        </div>
    </div>
</header>
