<?php
require __DIR__ . '/../vendor/autoload.php';

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/service-account.json');
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->addScope(Google_Service_Drive::DRIVE);

$drive = new Google_Service_Drive($client);

try {
    $about = $drive->about->get(['fields' => 'user,storageQuota']);
    $user = $about->getUser();
    $quota = $about->getStorageQuota();
    echo "SA email: " . ($user->getEmailAddress() ?? 'unknown') . PHP_EOL;
    echo "Quota limit: " . ($quota->getLimit() ?? 'NULL') . PHP_EOL;
    echo "Usage: " . ($quota->getUsage() ?? '0') . PHP_EOL;
    echo "UsageInDrive: " . ($quota->getUsageInDrive() ?? '0') . PHP_EOL;
    echo "UsageInDriveTrash: " . ($quota->getUsageInDriveTrash() ?? '0') . PHP_EOL;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
