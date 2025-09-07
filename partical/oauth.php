<?php
// oauth.php
require __DIR__ . '/../vendor/autoload.php';

$client = new Google_Client();
$client->setApplicationName('OAuth2 Docs Drive Test');
$client->setScopes([
    Google_Service_Drive::DRIVE,
    Google_Service_Docs::DOCUMENTS
]);
$client->setAuthConfig(__DIR__ . '/credentials.json');
$client->setAccessType('offline');
$client->setPrompt('consent');
$client->setRedirectUri('http://localhost/thuctap.com/partical/oauth2callback.php');

// Redirect user tới Google Login
header('Location: ' . $client->createAuthUrl());
exit;
