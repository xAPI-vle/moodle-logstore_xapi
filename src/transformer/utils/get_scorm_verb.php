<?php

namespace transformer\utils;

function get_scorm_verb($scorm_scoes_tracks, $lang) {
    $scormstatus = null;
    foreach ($scorm_scoes_tracks as $st) {
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
