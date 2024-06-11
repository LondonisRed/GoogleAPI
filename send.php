<?php
require_once 'vendor/autoload.php';

session_start();

if (!isset($_SESSION['access_token'])) {
    header('Location: login.php');
    exit;
}

$client = new Google_Client();
$client->setAccessToken($_SESSION['access_token']);

$service = new Google_Service_Gmail($client);

$to = $_POST['to'];
$subject = $_POST['subject'];
$body = $_POST['body'];
$file = $_FILES['attachment'];

$message = new Google_Service_Gmail_Message();
$raw_message_string = "From: me\r\n";
$raw_message_string .= "To: $to\r\n";
$raw_message_string .= "Subject: $subject\r\n\r\n";
$raw_message_string .= $body;

if ($file['size'] > 0) {
    $boundary = uniqid(rand(), true);
    $raw_message_string = "From: me\r\n";
    $raw_message_string .= "To: $to\r\n";
    $raw_message_string .= "Subject: $subject\r\n";
    $raw_message_string .= "MIME-Version: 1.0\r\n";
    $raw_message_string .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n\r\n";
    $raw_message_string .= "--$boundary\r\n";
    $raw_message_string .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
    $raw_message_string .= $body . "\r\n\r\n";
    $raw_message_string .= "--$boundary\r\n";
    $raw_message_string .= "Content-Type: " . $file['type'] . "; name=\"" . $file['name'] . "\"\r\n";
    $raw_message_string .= "Content-Disposition: attachment; filename=\"" . $file['name'] . "\"\r\n";
    $raw_message_string .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $raw_message_string .= chunk_split(base64_encode(file_get_contents($file['tmp_name']))) . "\r\n";
    $raw_message_string .= "--$boundary--";
}

$raw_message = base64_encode($raw_message_string);
$raw_message = strtr($raw_message, array('+' => '-', '/' => '_'));

$message->setRaw($raw_message);
$service->users_messages->send('me', $message);
?>
