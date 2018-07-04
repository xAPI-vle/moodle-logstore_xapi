<?php

namespace src\transformer\utils;
defined('MOODLE_INTERNAL') || die();

function cap_raw_score($rawscore, $minscore, $maxscore) {
    return max(min($rawscore, $maxscore), $minscore);
}
