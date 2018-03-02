<?php

namespace transformer\utils;

function get_scorm_verb($scormstatus, $lang){

    switch ($scormstatus) {
        case 'failed':
            $verburl = 'http://adlnet.gov/expapi/verbs/failed';
            $verb = 'failed';
            break;
        case 'passed':
            $verburl = 'http://adlnet.gov/expapi/verbs/passed';
            $verb = 'passed';
            break;
        default:
            $verburl = 'http://adlnet.gov/expapi/verbs/completed';
            $verb = 'completed';
    }

    return [
        'id' => $verburl,
        'display' => [
            $lang => $verb
        ],
    ];
}