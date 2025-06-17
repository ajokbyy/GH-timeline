<?php
require_once 'functions.php';
session_start();

$message = '';
$step = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && !isset($_POST['code'])) {
        // Step 1: Send verification code
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['code'] = generateVerificationCode();
        sendVerificationEmail($_SESSION['email'], $_SESSION['code']);
        $message = "Verification code sent to {$_SESSION['email']}";
        $step = 2;
    } elseif (isset($_POST['code'])) {
        // Step 2: Verify code
        if ($_POST['code'] == $_SESSION['code']) {
            registerEmail($_SESSION['email']);
            $message = "✅ {$_SESSION['email']} has been successfully subscribed.";
            $step = 3;
            session_destroy();
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
    <title>GH Timeline Subscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>GH Timeline Email Updates</h1>

    <?php if ($step === 1): ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Subscribe</button>
        </form>
    <?php elseif ($step === 2): ?>
        <form method="POST">
            <input type="text" name="code" placeholder="Enter verification code" required>
            <button type="submit">Verify</button>
        </form>
    <?php elseif ($step === 3): ?>
        <p>You're now subscribed to GitHub Timeline email updates.</p>
    <?php endif; ?>

    <p class="message"><?= $message ?></p>

    <a href="unsubscribe.php" class="unsubscribe-link">Unsubscribe</a>
</div>
</body>
</html>

