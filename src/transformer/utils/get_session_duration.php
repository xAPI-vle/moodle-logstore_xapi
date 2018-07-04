<?php

namespace src\transformer\utils;
defined('MOODLE_INTERNAL') || die();

function get_session_duration($config, $sessionid) {
    $repo = $config['repo'];
    $dates = $repo->read_records('facetoface_sessions_dates', [ 'sessionid' => $sessionid ]);
    $duration = 0;
    foreach ($dates as $index => $date) {
        $duration -= $date->timestart;
        $duration += $date->timefinish;
    }
    return $duration;
}
