<?php
require_once 'functions.php';

session_start();
$message = '';
$step = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && !isset($_POST['code'])) {
        // Step 1: Request verification code
        $_SESSION['unsubscribe_email'] = $_POST['email'];
        $_SESSION['unsubscribe_code'] = generateVerificationCode();

        sendUnsubscribeCode($_SESSION['unsubscribe_email'], $_SESSION['unsubscribe_code']);
        $step = 2;
        $message = "Verification code sent to {$_SESSION['unsubscribe_email']}";
    } elseif (isset($_POST['code'])) {
        // Step 2: Verify code
        if ($_POST['code'] == $_SESSION['unsubscribe_code']) {
            unsubscribeEmail($_SESSION['unsubscribe_email']);
            $message = "✅ Successfully unsubscribed {$_SESSION['unsubscribe_email']}";
            session_destroy();
            $step = 3;
        } else {
            $message = "❌ Incorrect verification code.";
            $step = 2;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Unsubscribe Verification</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Unsubscribe from GH Timeline</h1>

    <?php if ($step === 1): ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Send Verification Code</button>
        </form>
    <?php elseif ($step === 2): ?>
        <form method="POST">
            <input type="text" name="code" placeholder="Enter verification code" required>
            <button type="submit">Confirm Unsubscribe</button>
        </form>
    <?php endif; ?>

    <p class="message"><?= $message ?></p>
    <a href="index.php">Back to Subscribe</a>
</div>
</body>
</html>

