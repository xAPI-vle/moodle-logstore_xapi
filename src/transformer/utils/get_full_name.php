<?php

namespace src\transformer\utils;

function get_full_name(\stdClass $user) {
    $has_first_name = $user->firstname;
    $has_last_name = $user->lastname;

    if ($has_first_name && $has_last_name) {
        return $user->firstname.' '.$user->lastname;
    }
    if ($has_first_name) {
        return $user->firstname;
    }
    if ($has_last_name) {
        return $user->lastname;
    }
    return '';
}
