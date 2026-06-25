<?php
require_once 'config/db.php';
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

if (isset($_POST['save_contact'])) {
    $name = $_POST['customer_name'];
    $mobile = $_POST['mobile_number'];
    $email = $_POST['email_id'];
    $location = $_POST['location'];
    $note = $_POST['note'];
    $birthday = !empty($_POST['birthday']) ? $_POST['birthday'] : NULL;

    $stmt = $conn->prepare("INSERT INTO customers (customer_name, mobile_number, email_id, location, note, birthday) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $mobile, $email, $location, $note, $birthday);
    $stmt->execute();
    $msg = "Contact Saved!";
}
?>
<form action="input_contact.php" method="POST">
    <button type="submit" name="save_contact">SAVE CONTACT</button>
</form>
<a href="view_contacts.php">View All Contacts</a>
