<?php

namespace src\transformer\utils;

function get_scaled_score($raw_score, $min_score, $max_score) {
    $score_range = $max_score - $min_score;
    $score = $raw_score - $min_score;
    return $score / $score_range;
}
