<?php
require_once 'config/db.php';
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

// সার্চ লজিক
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT * FROM customers";
if ($search != '') {
    $sql .= " WHERE customer_name LIKE '%$search%' OR mobile_number LIKE '%$search%'";
}
$sql .= " ORDER BY id DESC";
$contacts = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { background-color: #030712; }</style>
</head>
<body class="text-white max-w-md mx-auto min-h-screen">

<div class="sticky top-0 bg-[#030712] p-4 border-b border-gray-800 z-10">
    <a href="manage_contacts.php" class="inline-block bg-gray-800 px-5 py-2 mb-4 rounded-xl text-xs font-black uppercase">← BACK</a>
    
    <form method="GET" action="view_contacts.php">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search Name or Number..." 
               class="w-full bg-gray-900 p-3 rounded-xl border border-gray-700 outline-none text-sm">
    </form>
</div>

<div class="p-4 space-y-3">
    <?php while($row = $contacts->fetch_assoc()): 
        $name = htmlspecialchars($row['customer_name']);
        $phone = htmlspecialchars($row['mobile_number']);
    ?>
        <div class="bg-gray-900 p-4 rounded-2xl border border-gray-800 flex justify-between items-center">
            <div>
                <h2 class="text-sm font-bold"><?= $name ?></h2>
                <p class="text-[11px] text-blue-400 font-mono">📞 <?= $phone ?></p>
            </div>
            
            <div class="flex gap-2">
                <a href="tel:<?= $phone ?>" class="bg-emerald-600 p-3 rounded-full text-xs font-black">📞</a>
                <button onclick="shareContact('<?= $name ?>', '<?= $phone ?>')" class="bg-indigo-600 p-3 rounded-full text-xs font-black">📤</button>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<script>
    function shareContact(name, phone) {
        const shareData = { title: 'Contact Details', text: `Name: ${name}\nMobile: ${phone}` };
        if (navigator.share) {
            navigator.share(shareData).catch(console.error);
        } else {
            navigator.clipboard.writeText(`${name}: ${phone}`);
            alert("Copied!");
        }
    }
</script>

</body>
</html>
