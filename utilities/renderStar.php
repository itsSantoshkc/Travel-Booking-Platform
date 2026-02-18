<?php

function renderStars($rating, $maxStars = 5) {
    $rating = intval($rating); // ensure it's an integer
    $starsHtml = '';
    for ($i = 1; $i <= $maxStars; $i++) {
        $starsHtml .= ($i <= $rating) ? '★' : '☆';
    }
    return $starsHtml;
}

?>