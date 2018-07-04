<?php

namespace src\transformer\utils;
defined('MOODLE_INTERNAL') || die();

date_default_timezone_set('Europe/London');

function create_timestamp($time) {
    return date('c', $time);
}
