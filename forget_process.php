<?php
/**
 * Password Reset Processing Module
 * Version: 2.0 (Secure)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/db.php';
require_once 'config/green_api_process.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_forget'])) {
    $whatsapp_number = trim($_POST['whatsapp_number']);

    if (empty($whatsapp_number)) {
        $_SESSION['forget_error'] = "WhatsApp number is required!";
        header("Location: forget_password.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id FROM system_admins WHERE phone_number = ? AND status = 'Active' LIMIT 1");
    $stmt->bind_param("s", $whatsapp_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $res = processPasswordReset($whatsapp_number);
        
        if ($res['status'] === true) {
            $_SESSION['reset_token_phone'] = $whatsapp_number;
            header("Location: verify_otp.php");
            exit();
        } else {
            $_SESSION['forget_error'] = $res['message'];
        }
    } else {
        $_SESSION['forget_error'] = "Number not registered or inactive!";
    }

    header("Location: forget_password.php");
    exit();
} else {
    header("Location: forget_password.php");
    exit();
}
?>
