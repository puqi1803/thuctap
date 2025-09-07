<?php
function truncateExpert ($expert, $maxWords = 40) {
    $words = explode(' ', $expert);
    if (count($words) > $maxWords) {
        $truncated = array_slice($words, 0, $maxWords);
        return implode (' ', $truncated) . '...';
    }
    return $expert;
}
function truncateExpertShort ($expert, $maxWords = 20) {
    $words = explode(' ', $expert);
    if (count($words) > $maxWords) {
        $truncated = array_slice($words, 0, $maxWords);
        return implode (' ', $truncated) . '...';
    }
    return $expert;
}

function convertToSlug($string) {
    $string = mb_strtolower($string, 'UTF-8');
    $string = preg_replace('/[^a-z0-9 -]/', '', $string);
    $string = preg_replace('/[ -]+/', '-', $string);
    $string = trim($string, '-');
    return $string;
}

function formatDate ($date) {
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);
    if ($dateTime) {
        return $dateTime->format('d-m-Y');
    } else {
        return 'Ngày không hợp lệ';
    }
}

function truncateTitle($title, $maxWords = 10) {
    $words = explode(' ', $title);
    if (count($words) > $maxWords) {
        $truncated = array_slice($words, 0, $maxWords);
        return implode(' ', $truncated) . '...';
    }
    return $title;
}

function createSlug($title) {
    $title = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $title);
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    return trim($slug, '-');
}

function createSlugCategory($name_category_post) {
    $name_category_post = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name_category_post);
    $slug_category = strtolower($name_category_post);
    $slug_category = preg_replace('/[^a-z0-9\s-]/', '', $slug_category);
    $slug_category = preg_replace('/[\s-]+/', '-', $slug_category);
    return trim($slug_category, '-');
}

function createSlugLocation($name_location) {
    $name_location = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name_location);
    $slug_location = strtolower($name_location);
    $slug_location = preg_replace('/[^a-z0-9\s-]/', '', $slug_location);
    $slug_location = preg_replace('/[\s-]+/', '-', $slug_location);
    return trim($slug_location, '-');
}

function getTourTitle($conn, $index) {
    $res = $conn->query("SELECT `title-tour` FROM `tour` WHERE `index-tour` = $index");
    if ($res && $res->num_rows > 0) {
        return $res->fetch_assoc()['title-tour'];
    }
    return '';
}
function getCollaboratorName($conn, $id) {
    $res = $conn->query("SELECT `name-collaborator` FROM `collaborator` WHERE `id-collaborator` = $id");
    if ($res && $res->num_rows > 0) {
        return $res->fetch_assoc()['name-collaborator'];
    }
    return '';
}
function getContractTypeName($conn, $id) {
    $res = $conn->query("SELECT `name-contract-type` FROM `contract-type` WHERE `id-contract-type` = $id");
    if ($res && $res->num_rows > 0) {
        return $res->fetch_assoc()['name-contract-type'];
    }
    return '';
}

use Google\Service\Drive;
use Google\Service\Docs;
use Google\Service\Docs\Request;
use Google\Service\Docs\BatchUpdateDocumentRequest;
function createContractFromTemplate($templateId, $replacements, $newTitle) {
    $client = getGoogleClient();

    $driveService = new Google_Service_Drive($client);
    $docsService  = new Google_Service_Docs($client);

    // 1. Copy template
    $copy = new Google_Service_Drive_DriveFile([
        'name' => $newTitle,
        'mimeType' => 'application/vnd.google-apps.document',
        'parents' => ['1yAFe2YO_bA6PGdi8EUBdXMSS0uHptEtF'],
    ]);
    $newFile = $driveService->files->copy($templateId, $copy);
    $documentId = $newFile->id;

    // 2. Chuẩn bị batch replace
    $requests = [];
    foreach ($replacements as $key => $value) {
        $requests[] = new Google_Service_Docs_Request([
            'replaceAllText' => [
                'containsText' => [
                    'text' => '{{' . $key . '}}', // placeholder dạng {{name-collaborator}}
                    'matchCase' => true,
                ],
                'replaceText' => $value
            ]
        ]);
    }

    // 3. Gửi request update vào Docs
    $batchUpdateRequest = new Google_Service_Docs_BatchUpdateDocumentRequest([
        'requests' => $requests
    ]);

    $docsService->documents->batchUpdate($documentId, $batchUpdateRequest);

    // 4. Tạo link mới
    return "https://docs.google.com/document/d/$documentId/edit";       
};

?>

