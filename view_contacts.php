<?php
require_once 'config/db.php';
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

$contacts = $conn->query("SELECT * FROM customers ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { background-color: #030712; }</style>
</head>
<body class="text-white max-w-md mx-auto min-h-screen">

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

<div class="p-4">
    <a href="input_contact.php" class="block w-full bg-blue-600 text-center py-4 rounded-2xl font-black text-sm uppercase">Add New Contact</a>
</div>

<script>
    function shareContact(name, phone) {
        const shareData = {
            title: 'Contact Details',
            text: `Name: ${name}\nMobile: ${phone}`,
        };
        
        if (navigator.share) {
            navigator.share(shareData).catch(console.error);
        } else {
            // যদি শেয়ার অপশন না থাকে তবে নাম্বারটি ক্লিপবোর্ডে কপি হবে
            navigator.clipboard.writeText(`${name}: ${phone}`);
            alert("Copied to clipboard!");
        }
    }
</script>

</body>
</html>
