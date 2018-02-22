<?php

namespace extractor\utils;

function get_event_timestamp($event) {
    return create_timestamp($event['timecreated']);
}
