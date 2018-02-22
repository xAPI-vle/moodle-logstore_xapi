<?php

namespace transformer\utils;

function create_timestamp($time) {
    return date('c', $time);
}
