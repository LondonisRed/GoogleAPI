<?php
require_once 'vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig('credentials.json');
$client->setRedirectUri('http://localhost/20231234/callback.php');

if (!isset($_GET['code'])) {
    header('Location: login.php');
    exit;
}

$client->authenticate($_GET['code']);
$_SESSION['access_token'] = $client->getAccessToken();
header('Location: index.html');
?>
