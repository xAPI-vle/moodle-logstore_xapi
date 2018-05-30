<?php

namespace transformer\utils;

function get_attempt_duration($attempt) {
    if (isset($attempt->timefinish)) {
        $seconds = $attempt->timefinish - $attempt->timestart;
        return "PT".(string) $seconds."S";
    } else {
        return null;
    }
}
