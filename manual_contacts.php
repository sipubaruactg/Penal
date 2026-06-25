<?php
require_once 'config/db.php';
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

$msg = "";
// ম্যানুয়াল ইনপুট লজিক
if (isset($_POST['save_contact'])) {
    $name = !empty($_POST['customer_name']) ? $_POST['customer_name'] : "Unknown";
    $mobile = !empty($_POST['mobile_number']) ? $_POST['mobile_number'] : "0000000000";
    $email = $_POST['email_id'];
    $location = $_POST['location'];
    $note = $_POST['note'];
    $birthday = !empty($_POST['birthday']) ? $_POST['birthday'] : NULL;

    $stmt = $conn->prepare("INSERT INTO customers (customer_name, mobile_number, email_id, location, note, birthday) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $mobile, $email, $location, $note, $birthday);
    
    if ($stmt->execute()) {
        $msg = "SUCCESSFULLY SAVED!";
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { background-color: #030712; height: 100vh; overflow: hidden; }</style>
</head>
<body class="flex flex-col h-screen p-6 text-white">

    <h2 class="text-center font-black text-xl uppercase tracking-widest py-4">MANUAL INPUT</h2>

    <?php if($msg): ?>
        <div class="bg-green-600 text-center font-black py-3 rounded-2xl mb-4 animate-pulse"><?= $msg ?></div>
    <?php endif; ?>

    <form action="manual_contacts.php" method="POST" class="flex-grow flex flex-col justify-center gap-3">
        <input type="text" name="customer_name" placeholder="Name" required class="w-full bg-gray-900 p-4 rounded-xl border border-gray-800 outline-none">
        <input type="text" name="mobile_number" placeholder="Mobile" required class="w-full bg-gray-900 p-4 rounded-xl border border-gray-800 outline-none">
        <input type="text" name="email_id" placeholder="Email" class="w-full bg-gray-900 p-4 rounded-xl border border-gray-800 outline-none">
        <input type="text" name="location" placeholder="Location" class="w-full bg-gray-900 p-4 rounded-xl border border-gray-800 outline-none">
        <input type="text" name="note" placeholder="Note" class="w-full bg-gray-900 p-4 rounded-xl border border-gray-800 outline-none">
        <input type="date" name="birthday" class="w-full bg-gray-900 p-4 rounded-xl border border-gray-800 outline-none">
        
        <button type="submit" name="save_contact" class="w-full bg-blue-600 py-4 rounded-xl font-black text-lg uppercase mt-2">SAVE CONTACT</button>
    </form>

    <a href="dashboard.php" class="block w-full bg-gray-800 text-center py-4 rounded-2xl font-black text-sm uppercase mt-4">BACK</a>

</body>
</html>
