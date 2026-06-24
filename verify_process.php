<?php
/**
 * OTP Verification Processing Module
 * Version: 2.0 (Secure)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/db.php';

if (!isset($_SESSION['reset_token_phone'])) {
    header("Location: forget_password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_verify'])) {
    $otp_code = trim($_POST['otp_code']);
    $whatsapp_number = $_SESSION['reset_token_phone'];

    if (empty($otp_code)) {
        $_SESSION['verify_error'] = "OTP code is required!";
        header("Location: verify_otp.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id FROM system_admins WHERE phone_number = ? AND password_reset_token = ? AND token_expiry > NOW() LIMIT 1");
    $stmt->bind_param("ss", $whatsapp_number, $otp_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['otp_verified'] = true;
        header("Location: reset_password.php");
        exit();
    } else {
        $_SESSION['verify_error'] = "Invalid or expired OTP!";
        header("Location: verify_otp.php");
        exit();
    }
} else {
    header("Location: verify_otp.php");
    exit();
}
?>
