<?php
/**
 * File: test_db.php - Database Connection & Configuration Test
 * Version: 1.1 (Improved)
 */

// এনভায়রনমেন্ট ভেরিয়েবল থেকে তথ্য নেওয়া (নিরাপদ পদ্ধতি)
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$db   = getenv('DB_NAME');
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Database Connection Test</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; background: #f4f4f9; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 500px; margin: auto; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>

<div class="card">
    <h2>DB Connection Test</h2>
    <hr>
    <?php
    $conn = @new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        echo "<p class='error'>ফেইল! কানেকশন করা সম্ভব হয়নি।</p>";
        echo "<p>এরর: " . htmlspecialchars($conn->connect_error) . "</p>";
    } else {
        echo "<p class='success'>সাকসেস! ডাটাবেস সফলভাবে কানেক্ট হয়েছে।</p>";
        
        $conn->set_charset("utf8mb4");

        $query = $conn->query("SHOW TABLES");
        echo "<b>টেবিল তালিকা:</b><ul>";
        if ($query && $query->num_rows > 0) {
            while($row = $query->fetch_array()) {
                echo "<li>" . htmlspecialchars($row[0]) . "</li>";
            }
        } else {
            echo "<li>কোনো টেবিল পাওয়া যায়নি।</li>";
        }
        echo "</ul>";
        $conn->close();
    }
    ?>
</div>

</body>
</html>
