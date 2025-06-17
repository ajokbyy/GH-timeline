<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

function generateVerificationCode() {
    return rand(100000, 999999);
}

function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
}

function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) return;

    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $emails = array_filter($emails, fn($e) => trim($e) !== trim($email));
    file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL);
}

function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'localhost';
        $mail->Port = 1025;
        $mail->SMTPAuth = false;
        $mail->setFrom('noreply@example.com', 'GH Timeline');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email error: " . $mail->ErrorInfo);
        return false;
    }
}

function sendVerificationEmail($email, $code) {
    $subject = "Your Verification Code";
    $body = "<p>Your verification code is: <strong>$code</strong></p>";
    sendEmail($email, $subject, $body);
}

function sendUnsubscribeCode($email, $code) {
    $subject = "Confirm Unsubscription";
    $body = "<p>To confirm unsubscription, use this code: <strong>$code</strong></p>";
    sendEmail($email, $subject, $body);
}

function fetchGitHubTimeline() {
    $url = "https://github.com/timeline.json"; // fallback URL
    $context = stream_context_create([
        "http" => ["user_agent" => "GH-Timeline"]
    ]);
    $data = @file_get_contents($url, false, $context);
    return $data ? json_decode($data, true) : [];
}

function formatGitHubData($data) {
    $html = "<h2>GitHub Timeline Updates</h2>";
    $html .= "<table border='1'>";
    $html .= "<tr><th>Event</th><th>User</th></tr>";

    if (is_array($data)) {
        foreach ($data as $event) {
            $type = $event['type'] ?? 'Push';
            $user = $event['actor']['login'] ?? 'testuser';
            $html .= "<tr><td>{$type}</td><td>{$user}</td></tr>";
        }
    } else {
        // fallback in case no data or mock
        $html .= "<tr><td>Push</td><td>testuser</td></tr>";
    }

    $html .= "</table>";
    return $html;
}

function sendGitHubUpdatesToSubscribers() {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) return;

    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $data = fetchGitHubTimeline();
    $body = formatGitHubData($data);

    foreach ($emails as $email) {
        $unsubscribeLink = "http://localhost/github-timeline/src/unsubscribe.php?email=" . urlencode($email);
        $fullBody = $body . "<p><a href=\"$unsubscribeLink\" id=\"unsubscribe-button\">Unsubscribe</a></p>";
       
        $subject = "Latest GitHub Updates";
        sendEmail($email, $subject, $fullBody);
    }
}
?>
