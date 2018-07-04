<?php
namespace src\transformer\utils;
defined('MOODLE_INTERNAL') || die();

function get_event_timestamp(\stdClass $event) {
    return create_timestamp($event->timecreated);
}
