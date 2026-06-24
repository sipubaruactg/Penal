<?php
// সেশন শুরু করা হলো
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ডাটাবেজ কানেকশন এবং গ্রীন এপিআই কোর ফাংশন ফাইল যুক্ত করা হলো
require_once 'config/db.php';
require_once 'config/green_api_process.php';

if (isset($_POST['submit_forget'])) {
    $whatsapp_number = trim($_POST['whatsapp_number']);

    // ইনপুট খালি আছে কিনা চেক
    if (empty($whatsapp_number)) {
        $_SESSION['forget_error'] = "হোয়াটসঅ্যাপ নাম্বারটি প্রদান করুন!";
        header("Location: forget_password.php");
        exit;
    }

    // হোয়াটসঅ্যাপ নাম্বারটি সিস্টেমে সচল (Active) কোনো এডমিনের কিনা তা চেক করা হচ্ছে
    $stmt = $conn->prepare("SELECT id FROM system_admins WHERE phone_number = ? AND status = 'Active' LIMIT 1");
    $stmt->bind_param("s", $whatsapp_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        
        // config/green_api_process.php ফাইলের মেইন ওটিপি পুশার ফাংশনটি কল করা হলো
        $res = processPasswordReset($whatsapp_number);
        
        if ($res['status'] === true) {
            // ওটিপি সফলভাবে চলে গেলে পরবর্তী পেজগুলোর ট্র্যাকিংয়ের জন্য নাম্বারটি সেশনে রাখা হলো
            $_SESSION['reset_token_phone'] = $whatsapp_number; 
            
            // সরাসরি ওটিপি কোড বসানোর পেজে রিডাইরেক্ট
            header("Location: verify_otp.php");
            exit;
        } else {
            // গ্রীন এপিআই বা সিস্টেমের কোনো এরর মেসেজ থাকলে তা সেশনে পাস করা হলো
            $_SESSION['forget_error'] = $res['message'];
        }
    } else {
        $_SESSION['forget_error'] = "এই হোয়াটসঅ্যাপ নাম্বারটি সিস্টেমে নিবন্ধিত বা একটিভ নয়!";
    }

    // কোনো এরর হলে আবার ফরগেট পেজে ব্যাক করবে
    header("Location: forget_password.php");
    exit;
} else {
    // সরাসরি কেউ ফাইল অ্যাক্সেস করতে চাইলে রিডাইরেক্ট
    header("Location: forget_password.php");
    exit;
}
?>
