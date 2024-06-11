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
$results = $service->users_messages->listUsersMessages('me');

$messages = [];
foreach ($results->getMessages() as $message) {
    $msg = $service->users_messages->get('me', $message->getId());
    $messages[] = $msg;
}

echo json_encode($messages);
?>
