<?php
require __DIR__ . '/../vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/credentials.json');
$client->setRedirectUri('http://localhost/thuctap.com/partical/oauth2callback.php');
$client->setScopes([
    Google_Service_Drive::DRIVE,
    Google_Service_Docs::DOCUMENTS
]);
$client->setAccessType('offline');

// nơi lưu token
$tokenPath = __DIR__ . '/token.json';

if (!isset($_GET['code'])) {
    // nếu chưa có code thì quay lại oauth.php
    echo "Không có mã code. Hãy chạy lại oauth.php để lấy link đăng nhập!";
    exit;
}

// Lấy access token bằng code
$authCode = $_GET['code'];
$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

if (isset($accessToken['error'])) {
    echo "Lỗi lấy token: " . $accessToken['error_description'];
    exit;
}

// Lưu token ra file
file_put_contents($tokenPath, json_encode($accessToken));
echo "✅ Đăng nhập thành công, token đã lưu tại $tokenPath";
