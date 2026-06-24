<?php
// লগইন সেশন থেকে এডমিন ডাটা নেওয়া
$current_admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : "Super Admin";
$current_admin_role = isset($_SESSION['role']) ? $_SESSION['role'] : "SuperAdmin";

// ডাটাবেজ থেকে লেটেস্ট ডাটা নিশ্চিত করার জন্য আইডি চেক
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $stmt = $conn->prepare("SELECT admin_name, role FROM system_admins WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $current_admin_name = $row['admin_name'];
        $current_admin_role = $row['role'];
    }
}
?>

<header class="bg-gray-900 border-b border-gray-800 px-4 py-4 shrink-0 shadow-lg">
    <div class="flex items-center justify-between">
        
        <div class="flex items-center space-x-3">
            <div class="relative">
                <div class="w-11 h-11 bg-indigo-600/20 rounded-full border border-indigo-500/30 flex items-center justify-center text-lg">
                    🛡️
                </div>
                <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-emerald-500 border-2 border-gray-900"></span>
            </div>
            
            <div class="flex flex-col">
                <h2 class="text-xs font-bold text-gray-200 uppercase tracking-widest">
                    <?= htmlspecialchars($current_admin_name) ?>
                </h2>
                <span class="text-[9px] text-indigo-400 font-bold uppercase tracking-wider mt-0.5">
                    <?= htmlspecialchars($current_admin_role) ?>
                </span>
            </div>
        </div>

        <div class="text-right">
            <div id="liveClock" class="text-sm font-mono font-bold text-emerald-400 tracking-wider">
                00:00:00
            </div>
            <div class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mt-0.5">
                <?= date('d M, Y') ?>
            </div>
        </div>
    </div>
</header>

<script>
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-GB'); 
        const clockElement = document.getElementById('liveClock');
        if (clockElement) clockElement.textContent = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
