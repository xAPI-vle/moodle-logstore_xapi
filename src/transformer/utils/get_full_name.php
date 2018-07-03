<?php

namespace src\transformer\utils;

function get_full_name(\stdClass $user) {
    $has_first_name = property_exists($user, 'firstname');
    $has_last_name = property_exists($user, 'lastname');

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
