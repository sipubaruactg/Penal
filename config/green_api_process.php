<?php
// ডাটাবেজ কানেকশন ফাইল যুক্ত করা হলো
require_once __DIR__ . '/db.php';

/**
 * ক. গ্রীন এপিআই-এর মাধ্যমে হোয়াটসঅ্যাপে মেসেজ পাঠানোর কোর ফাংশন
 */
function sendWhatsAppNotification($target_phone, $message_text) {
    global $conn;

    // ডাটাবেজ থেকে সচল গ্রীন এপিআই এর ক্রেডেনশিয়াল তুলে আনা
    $api_query = $conn->query("SELECT instance_id, api_token FROM green_api_settings WHERE status = 'Active' LIMIT 1");
    
    if ($api_query->num_rows === 0) {
        return ["status" => false, "message" => "গ্রীন এপিআই কনফিগারেশন ডাটাবেজে অ্যাক্টিভ পাওয়া যায়নি!"];
    }

    $api_data = $api_query->fetch_assoc();
    $instanceId = trim($api_data['instance_id']);
    $apiTokenId = trim($api_data['api_token']);

    // মোবাইল নাম্বার ফরম্যাট ঠিক করা (বাংলাদেশের কান্ট্রি কোড ৮৮ যুক্ত করা)
    $target_phone = preg_replace('/[^0-9]/', '', $target_phone);
    if (strlen($target_phone) === 11 && substr($target_phone, 0, 2) === "01") {
        $target_phone = "88" . $target_phone;
    }

    // গ্রীন এপিআই অফিশিয়াল sendMessage URL
    $url = "https://api.green-api.com/waInstance{$instanceId}/sendMessage/{$apiTokenId}";
    
    // এপিআই পে-লোড ডাটা
    $payload = [
        "chatId" => $target_phone . "@c.us",
        "message" => $message_text
    ];

    // cURL এর মাধ্যমে ব্যাকএন্ড থেকে সিকিউর রিকোয়েস্ট পাঠানো
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15); 

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200) {
        return ["status" => true, "message" => "সফলভাবে ওটিপি পাঠানো হয়েছে।"];
    } else {
        return ["status" => false, "message" => "গ্রীন এপিআই এরর কোড: " . $http_code];
    }
}

/**
 * খ. ওটিপি (OTP) তৈরি এবং ডাটাবেজ আপডেট করার ফাংশন
 */
function processPasswordReset($whatsapp_input) {
    global $conn;

    $stmt = $conn->prepare("SELECT id, admin_name FROM system_admins WHERE phone_number = ? AND status = 'Active' LIMIT 1");
    $stmt->bind_param("s", $whatsapp_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        $admin_id = $admin['id'];
        $admin_name = $admin['admin_name'];

        // একটি র্যান্ডম ৬ ডিজিটের ওটিপি (OTP) জেনারেট করা
        $reset_token = rand(100000, 999999);
        
        // টোকেনের মেয়াদ ১৫ মিনিট নির্ধারণ করা
        $expiry_time = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // ডাটাবেজের টোকেন কলাম এবং এক্সপায়ারি টাইম আপডেট করা
        $update_stmt = $conn->prepare("UPDATE system_admins SET password_reset_token = ?, token_expiry = ? WHERE id = ?");
        $update_stmt->bind_param("ssi", $reset_token, $expiry_time, $admin_id);
        
        if ($update_stmt->execute()) {
            // সুন্দর করে সাজানো হোয়াটসঅ্যাপ মেসেজ ফরম্যাট
            $message_text = "🛡️ *এডমিন পাসওয়ার্ড রিসেট কোড*\n\nপ্রিয় {$admin_name},\nআপনার অ্যাকাউন্ট পাসওয়ার্ড রিসেট করার সিকিউরিটি ওটিপি কোডটি হলো:\n\n🔑 *{$reset_token}*\n\nকোডটির মেয়াদ আগামী ১৫ মিনিট পর্যন্ত সচল থাকবে।";
            
            // মেসেজ পাঠানোর ফাংশন কল
            return sendWhatsAppNotification($whatsapp_input, $message_text);
        } else {
            return ["status" => false, "message" => "সিস্টেম ওটিপি টোকেন তৈরি করতে ব্যর্থ হয়েছে!"];
        }
    }
    return ["status" => false, "message" => "সিস্টেম ত্রুটি! পুনরায় চেষ্টা করুন।"];
}
?>

