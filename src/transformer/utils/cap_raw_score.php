<?php

namespace transformer\utils;

function cap_raw_score($raw_score, $min_score, $max_score) {
    return max(min($grade_sum, $max_score), $min_score);
}
