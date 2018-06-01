<?php

namespace src\transformer\utils;

date_default_timezone_set('Europe/London');

function create_timestamp($time) {
    return date('c', $time);
}
