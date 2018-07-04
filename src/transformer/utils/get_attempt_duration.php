<?php
namespace src\transformer\utils;
defined('MOODLE_INTERNAL') || die();

function get_attempt_duration($attempt) {
    if (isset($attempt->timefinish)) {
        $seconds = $attempt->timefinish - $attempt->timestart;
        return "PT".(string) $seconds."S";
    } else {
        return null;
    }
}
