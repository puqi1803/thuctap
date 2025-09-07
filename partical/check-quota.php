<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/google-client.php';
ini_set('display_errors',1); error_reporting(E_ALL);

$client = getGoogleClient();
$drive = new Google\Service\Drive($client);

try {
    $about = $drive->about->get(['fields' => 'user,storageQuota']);
    var_dump($about->getUser());
    var_dump($about->getStorageQuota());
} catch (Exception $e) {
    echo "ERR: " . $e->getMessage();
}

$res = $drive->files->listFiles([
    'q' => "trashed=false and 'me' in owners",
    'fields' => 'files(id,name,mimeType,size,createdTime)',
    'pageSize' => 100,
    'supportsAllDrives' => true
]);
?>