<?php

namespace transformer\utils;

function get_scorm_result($event){

    return [
        'score' => [
            'raw' => $event['scorm_score_raw'],
            'min' => $event['scorm_score_min'],
            'max' => $event['scorm_score_max'],
            'scaled' => $event['scorm_score_scaled']
        ],
    ];
}