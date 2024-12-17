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

function truncateTitle($title, $maxWords = 15) {
    $words = explode(' ', $title);
    if (count($words) > $maxWords) {
        $truncated = array_slice($words, 0, $maxWords);
        return implode(' ', $truncated) . '...';
    }
    return $title;
}

function createSlug($title) {
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    return trim($slug, '-');
}
?>