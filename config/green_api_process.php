<?php
/**
 * File: green_api_process.php
 * Module: WhatsApp OTP Notification Processor
 * Version: 2.1 (Optimized)
 */

require_once __DIR__ . '/db.php';

/**
 * Sends a message via Green API
 */
function sendWhatsAppNotification($target_phone, $message_text) {
    global $conn;

    // Fetch active API credentials securely
    $stmt = $conn->prepare("SELECT instance_id, api_token FROM green_api_settings WHERE status = 'Active' LIMIT 1");
    $stmt->execute();
    $api_data = $stmt->get_result()->fetch_assoc();
    
    if (!$api_data) {
        return ["status" => false, "message" => "API configuration not found or inactive!"];
    }

    $instanceId = trim($api_data['instance_id']);
    $apiTokenId = trim($api_data['api_token']);

    // Format phone number (Ensuring 88 prefix)
    $target_phone = preg_replace('/[^0-9]/', '', $target_phone);
    if (strlen($target_phone) === 11 && strpos($target_phone, '01') === 0) {
        $target_phone = "88" . $target_phone;
    }

    $url = "https://api.green-api.com/waInstance{$instanceId}/sendMessage/{$apiTokenId}";
    
    $payload = json_encode([
        "chatId" => $target_phone . "@c.us",
        "message" => $message_text
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true // Enabled for production security
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ($http_code === 200) 
        ? ["status" => true, "message" => "OTP sent successfully."] 
        : ["status" => false, "message" => "API Error Code: " . $http_code];
}

/**
 * Generates OTP and updates database
 */
function processPasswordReset($whatsapp_input) {
    global $conn;

    $stmt = $conn->prepare("SELECT id, admin_name FROM system_admins WHERE phone_number = ? AND status = 'Active' LIMIT 1");
    $stmt->bind_param("s", $whatsapp_input);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if ($admin) {
        $reset_token = random_int(100000, 999999); // Secure integer generation
        $expiry_time = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $update_stmt = $conn->prepare("UPDATE system_admins SET password_reset_token = ?, token_expiry = ? WHERE id = ?");
        $update_stmt->bind_param("ssi", $reset_token, $expiry_time, $admin['id']);
        
        if ($update_stmt->execute()) {
            $message_text = "🛡️ *Admin Password Reset*\n\nDear {$admin['admin_name']},\nYour OTP for password reset is:\n\n🔑 *{$reset_token}*\n\nValid for 15 minutes.";
            return sendWhatsAppNotification($whatsapp_input, $message_text);
        }
    }
    return ["status" => false, "message" => "Invalid request or account not found."];
}
