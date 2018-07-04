<?php
namespace src\transformer\utils;
defined('MOODLE_INTERNAL') || die();

function get_attempt_result(array $config, $attempt, $gradeitem) {
    $gradesum = isset($attempt->sumgrades) ? $attempt->sumgrades : 0;

    $minscore = floatval($gradeitem->grademin ?: 0);
    $maxscore = floatval($gradeitem->grademax ?: 0);
    $passscore = floatval($gradeitem->gradepass ?: 0);

    $rawscore = cap_raw_score($gradesum, $minscore, $maxscore);
    $scaledscore = get_scaled_score($rawscore, $minscore, $maxscore);

    $completed = isset($attempt->state) ? $attempt->state === 'finished' : false;
    $success = $gradesum >= $passscore;
    $duration = get_attempt_duration($attempt);

    return [
        'score' => [
            'raw' => $rawscore,
            'min' => $minscore,
            'max' => $maxscore,
            'scaled' => $scaledscore,
        ],
        'completion' => $completed,
        'success' => $success,
        'duration' => $duration,
    ];
}
