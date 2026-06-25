<?php
/**
 * Contact Management Module - INPUT ONLY
 */
require_once 'config/db.php';
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

// ডাটা সেভ প্রসেসিং
if (isset($_POST['save_contact'])) {
    $name = !empty($_POST['customer_name']) ? $_POST['customer_name'] : "Unknown";
    $mobile = !empty($_POST['mobile_number']) ? $_POST['mobile_number'] : "0000000000";
    $email = $_POST['email_id'];
    $location = $_POST['location'];
    $note = $_POST['note'];
    $birthday = !empty($_POST['birthday']) ? $_POST['birthday'] : NULL;

    $stmt = $conn->prepare("INSERT INTO customers (customer_name, mobile_number, email_id, location, note, birthday) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $mobile, $email, $location, $note, $birthday);
    $stmt->execute();
    $success = "Contact Saved Successfully!";
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Contact</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { background-color: #030712; }</style>
</head>
<body class="text-white max-w-md mx-auto min-h-screen p-4">

    <h1 class="text-center font-black text-lg mb-6">Add New Contact</h1>

    <?php if(isset($success)): ?>
        <div class="bg-green-600 p-3 rounded-xl text-center text-sm font-bold mb-4"><?= $success ?></div>
    <?php endif; ?>

    <form action="manage_contacts.php" method="POST" class="space-y-4">
        <input type="text" name="customer_name" placeholder="Name" required class="w-full bg-gray-900 p-4 rounded-xl border border-gray-700 outline-none">
        <input type="text" name="mobile_number" placeholder="Mobile" required class="w-full bg-gray-900 p-4 rounded-xl border border-gray-700 outline-none">
        <input type="text" name="email_id" placeholder="Email" class="w-full bg-gray-900 p-4 rounded-xl border border-gray-700 outline-none">
        <input type="text" name="location" placeholder="Location" class="w-full bg-gray-900 p-4 rounded-xl border border-gray-700 outline-none">
        <input type="text" name="note" placeholder="Note" class="w-full bg-gray-900 p-4 rounded-xl border border-gray-700 outline-none">
        <input type="date" name="birthday" class="w-full bg-gray-900 p-4 rounded-xl border border-gray-700 outline-none">
        
        <button type="submit" name="save_contact" class="w-full bg-blue-600 py-4 rounded-xl font-black text-sm uppercase">SAVE CONTACT</button>
    </form>

    <div class="mt-6">
        <a href="view_contacts.php" class="block w-full bg-gray-800 text-center py-4 rounded-xl font-black text-sm uppercase">View All Contacts</a>
    </div>

</body>
</html>
