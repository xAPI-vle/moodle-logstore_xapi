<?php

namespace src\transformer\utils;
defined('MOODLE_INTERNAL') || die();

function get_scorm_result($scormscoestracks, $rawscore) {
    $maxscore = null;
    $minscore = null;

    foreach ($scormscoestracks as $st) {
        if ($st->element == 'cmi.core.score.min') {
            $minscore = floatval($st->value);
        } else if ($st->element == 'cmi.core.score.max') {
            $maxscore = floatval($st->value);
        }
    }

    if ($maxscore !== null && $minscore !== null) {
        $scaled_score = get_scaled_score($rawscore, $minscore, $maxscore);
        return [
            'score' => [
                'raw' => $rawscore,
                'min' => $minscore,
                'max' => $maxscore,
                'scaled' => $scaled_score,
            ],
        ];
    }

    if ($maxscore !== null && $minscore === null) {
        return [
            'score' => [
                'raw' => $rawscore,
                'max' => $maxscore,
            ],
        ];
    }

    if ($maxscore === null && $minscore !== null) {
        return [
            'score' => [
                'raw' => $rawscore,
                'min' => $minscore,
            ],
        ];
    }

    return [
        'score' => [
            'raw' => $rawscore,
        ],
    ];
}