<?php
require __DIR__ . '/../vendor/autoload.php';

$client = new Google_Client();
$client->setApplicationName('OAuth2 Docs Drive Test');
$client->setScopes([
    Google_Service_Drive::DRIVE,
    Google_Service_Docs::DOCUMENTS
]);
$client->setAuthConfig(__DIR__ . '/credentials.json');
$client->setAccessType('offline');

$tokenPath = __DIR__ . '/token.json';
if (!file_exists($tokenPath)) {
    exit("Chưa có token.json, hãy chạy oauth.php trước.\n");
}
$accessToken = json_decode(file_get_contents($tokenPath), true);
$client->setAccessToken($accessToken);

// Nếu token hết hạn → refresh
if ($client->isAccessTokenExpired()) {
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    file_put_contents($tokenPath, json_encode($client->getAccessToken()));
}

$drive = new Google_Service_Drive($client);
$docs  = new Google_Service_Docs($client);

// ID template gốc (Docs)
$templateId = '1vOsjv8FYS1nAlpk3LlIpzVg4XrDJs6uQ';

// 1. Copy template thành hợp đồng mới
$copyMeta = new Google_Service_Drive_DriveFile([
    'name' => 'HD - Test ' . date('Y-m-d H:i:s'),
    'mimeType' => 'application/vnd.google-apps.document'
]);
$newFile = $drive->files->copy($templateId, $copyMeta, [
    'fields' => 'id, name, webViewLink'
]);

$newId = $newFile->id;

echo "File mới: {$newFile->name}\nLink: {$newFile->webViewLink}\n";

// 2. Thay placeholder
$replacements = [
    'name' => 'Nguyễn Văn A',
    'dob'  => '01/01/1990',
    'address' => 'Hà Nội'
];

$requests = [];
foreach ($replacements as $k => $v) {
    $requests[] = [
        'replaceAllText' => [
            'containsText' => ['text' => '{{' . $k . '}}', 'matchCase' => false],
            'replaceText'   => $v
        ]
    ];
}

$docs->documents->batchUpdate($newId, new Google_Service_Docs_BatchUpdateDocumentRequest([
    'requests' => $requests
]));

echo "✅ Hợp đồng đã tạo & cập nhật nội dung.\n";
    