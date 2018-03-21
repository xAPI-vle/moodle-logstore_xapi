<?php

namespace transformer\utils;

function get_event_timestamp(\stdClass $event) {
    return create_timestamp($event->timecreated);
}
