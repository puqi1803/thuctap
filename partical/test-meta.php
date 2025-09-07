<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/google-client.php'; // file bạn có hàm getGoogleClient()

ini_set('display_errors', 1);
error_reporting(E_ALL);

$client = getGoogleClient();
if (!$client) {
    die("Không tạo được Google client\n");
}

$drive = new Google\Service\Drive($client);
$templateId = '1vOsjv8FYS1nAlpk3LlIpzVg4XrDJs6uQ'; // thay bằng ID của bạn

try {
    $meta = $drive->files->get($templateId, [
        'fields' => 'id,name,mimeType,webViewLink,parents',
        'supportsAllDrives' => true
    ]);
    echo "META:\n";
    var_dump($meta);
} catch (Exception $e) {
    echo "Lỗi khi gọi Drive API: " . $e->getMessage() . "\n";
    // Nếu có lỗi permission, message sẽ cho biết
}
