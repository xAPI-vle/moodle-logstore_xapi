<?php

namespace src\transformer\utils;

function get_scorm_result($scorm_scoes_tracks, $raw_score) {
    $max_score = null;
    $min_score = null;

    foreach ($scorm_scoes_tracks as $st) {
        if ($st->element == 'cmi.core.score.min') {
            $min_score = floatval($st->value);
        } else if ($st->element == 'cmi.core.score.max') {
            $max_score = floatval($st->value);
        }
    }
    
    if ($max_score !== null && $min_score !== null) {
        $scaled_score = get_scaled_score($raw_score, $min_score, $max_score);
        return [
            'score' => [
                'raw' => $raw_score,
                'min' => $min_score,
                'max' => $max_score,
                'scaled' => $scaled_score,
            ],
        ];
    }

    if ($max_score !== null && $min_score === null) {
        return [
            'score' => [
                'raw' => $raw_score,
                'max' => $max_score,
            ],
        ];
    }

    if ($max_score === null && $min_score !== null) {
        return [
            'score' => [
                'raw' => $raw_score,
                'min' => $min_score,
            ],
        ];
    }

    return [
        'score' => [
            'raw' => $raw_score,
        ],
    ];
}