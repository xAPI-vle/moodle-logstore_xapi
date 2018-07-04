<?php
namespace src\transformer\utils;
defined('MOODLE_INTERNAL') || die();

function get_scaled_score($rawscore, $minscore, $maxscore) {
    if ($rawscore >= 0) {
        return $rawscore / $maxscore;
    } else {
        return $rawscore / $minscore;
    }
}
