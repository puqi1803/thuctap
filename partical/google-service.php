<?php
require_once '../vendor/autoload.php';

function getGoogleClient() {
    $client = new Google_Client();
    $client->setApplicationName('Google Docs Contract Generator');
    $client->setScopes([
        Google_Service_Drive::DRIVE,
        Google_Service_Docs::DOCUMENTS
    ]);
    $client->setAuthConfig('../credentials.json');
    $client->setAccessType('offline');
    return $client;
}