<?php

namespace src\transformer\utils;
defined('MOODLE_INTERNAL') || die();

function get_scorm_verb($scormscoestracks, $lang) {
    $scormstatus = null;
    foreach ($scormscoestracks as $st) {
        if ($st->element == 'cmi.core.lesson_status') {
            $scormstatus = $st->value;
        }
    }

    switch ($scormstatus) {
        case 'failed':
            return [
                'id' => 'http://adlnet.gov/expapi/verbs/failed',
                'display' => [
                    $lang => 'failed'
                ],
            ];
        case 'passed':
            return [
                'id' => 'http://adlnet.gov/expapi/verbs/passed',
                'display' => [
                    $lang => 'passed'
                ],
            ];
        default:
            return [
                'id' => 'http://adlnet.gov/expapi/verbs/completed',
                'display' => [
                    $lang => 'completed'
                ],
            ];
    }
}
