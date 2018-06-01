<?php

namespace src\transformer\utils;

function cap_raw_score($raw_score, $min_score, $max_score) {
    return max(min($raw_score, $max_score), $min_score);
}
