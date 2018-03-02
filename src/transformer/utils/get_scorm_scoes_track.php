<?php

namespace transformer\utils;

function get_scorm_scoes_track(array $config, $userid, $scormid, $scoid, $attempt){
    $trackingvalues = [];
    $scormtracking = $config['repo']->read_store_records('scorm_scoes_track', [
        'userid' => $userid,
        'scormid' => $scormid,
        'scoid' => $scoid,
        'attempt' => $attempt
    ]);

    foreach ($scormtracking as $st) {
        if ($st->element == 'cmi.core.score.min') {
            $trackingvalues['scoremin'] = $st->value;
        } else if ($st->element == 'cmi.core.score.max') {
            $trackingvalues['scoremax'] = $st->value;
        } else if ($st->element == 'cmi.core.lesson_status') {
            $trackingvalues['status'] = $st->value;
        }
    }

    return $trackingvalues;
}