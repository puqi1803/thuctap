<?php
function truncateExpert ($expert, $maxWords = 40) {
    $words = explode(' ', $expert);
    if (count($words) > $maxWords) {
        $truncated = array_slice($words, 0, $maxWords);
        return implode (' ', $truncated) . '...';
    }
    return $expert;
}

?>