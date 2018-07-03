<?php

namespace src\transformer\utils;

function get_scaled_score($raw_score, $min_score, $max_score) {
    if ($raw_score >= 0) {
        return $raw_score / $max_score;
    } else {
        return $raw_score / $min_score;
    }
}
