<?php

namespace src\transformer\utils;

function get_session_duration($config, $session_id) {
    $repo = $config['repo'];
    $dates = $repo->read_records('facetoface_sessions_dates', [ 'sessionid' => $session_id ]);
    $duration = 0;
    foreach ($dates as $index => $date) {
        $duration -= $date->timestart;
        $duration += $date->timefinish;
    }
    return $duration;
}
