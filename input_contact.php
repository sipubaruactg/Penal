<?php
require_once 'config/db.php';
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

$msg = "";
// ফাইল আপলোড লজিক
if (isset($_POST['import_csv'])) {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
        $handle = fopen($_FILES['csv_file']['tmp_name'], "r");
        fgetcsv($handle, 1000, ","); 
        
        $stmt = $conn->prepare("INSERT INTO customers (customer_name, mobile_number, email_id, location, note, birthday) VALUES (?, ?, ?, ?, ?, ?)");
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $c_name = !empty($data[0]) ? $data[0] : "Unknown";
            $c_mobile = !empty($data[1]) ? $data[1] : "0000000000";
            $c_email = !empty($data[2]) ? $data[2] : "";
            $c_loc = !empty($data[3]) ? $data[3] : "";
            $c_note = !empty($data[4]) ? $data[4] : "";
            $c_bday = !empty($data[5]) ? $data[5] : NULL;
            
            $stmt->bind_param("ssssss", $c_name, $c_mobile, $c_email, $c_loc, $c_note, $c_bday);
            $stmt->execute();
        }
        fclose($handle);
        $msg = "SUCCESSFULLY UPLOADED!";
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

    <h2 class="text-center font-black text-xl uppercase tracking-widest py-4">INPUT CONTACT</h2>

    <?php if($msg): ?>
        <div class="bg-green-600 text-center font-black py-3 rounded-2xl mb-4 animate-pulse"><?= $msg ?></div>
    <?php endif; ?>

    <form action="input_contact.php" method="POST" enctype="multipart/form-data" class="flex-grow flex flex-col justify-center gap-6">
        <label for="csv_file" class="w-full h-80 border-2 border-dashed border-gray-700 rounded-3xl flex flex-col items-center justify-center cursor-pointer hover:border-indigo-600 transition-all">
            <span class="text-6xl mb-4">📁</span>
            <span class="text-sm font-black text-gray-400" id="file-text">TAP TO SELECT FILE</span>
            <input type="file" name="csv_file" id="csv_file" accept=".csv" class="hidden" onchange="document.getElementById('file-text').innerText = this.files[0].name">
        </label>
        
        <button type="submit" name="import_csv" class="w-full bg-blue-600 py-6 rounded-3xl font-black text-lg uppercase active:scale-95 transition-all">UPLOAD NOW</button>
    </form>

    <a href="dashboard.php" class="block w-full bg-gray-800 text-center py-4 rounded-2xl font-black text-sm uppercase mt-4">BACK</a>

</body>
</html>
