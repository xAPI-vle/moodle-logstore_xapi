<?php

namespace src\transformer\utils;

function get_attempt_result(array $config, $attempt, $grade_item) {
    $grade_sum = isset($attempt->sumgrades) ? $attempt->sumgrades : 0;

    $min_score = (float)($grade_item->grademin ? $grade_item->grademin : 0);
    $max_score = (float)($grade_item->grademax ? $grade_item->grademax : 0);
    $pass_score = (float)($grade_item->gradepass ? $grade_item->gradepass : null);

    $raw_score = floatval((cap_raw_score($grade_sum, $min_score, $max_score)));
    $scaled_score = floatval((get_scaled_score($raw_score, $min_score, $max_score)));

    $completed = isset($attempt->state) ? $attempt->state === 'finished' : false;
    $success = $grade_sum >= $pass_score;
    $duration = get_attempt_duration($attempt);

    return [
        'score' => [
            'raw' => $raw_score,
            'min' => $min_score,
            'max' => $max_score,
            'scaled' => $scaled_score,
        ],
        'completion' => $completed,
        'success' => $success,
        'duration' => $duration,
    ];
}
