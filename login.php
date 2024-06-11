<?php
require_once 'vendor/autoload.php';
use Google\Client as Google_Client;

session_start();

$client = new Google_Client();
$client->setAuthConfig('credentials.json');
$client->addScope(Google_Service_Gmail::MAIL_GOOGLE_COM);
$client->setRedirectUri('http://localhost/20231234/callback.php');

$auth_url = $client->createAuthUrl();
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
?>
