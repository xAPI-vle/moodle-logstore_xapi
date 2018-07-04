<?php
namespace src\transformer\utils;
defined('MOODLE_INTERNAL') || die();

function get_full_name(\stdClass $user) {
    $hasfirstname = property_exists($user, 'firstname');
    $haslastname = property_exists($user, 'lastname');

    if ($hasfirstname && $haslastname) {
        return $user->firstname.' '.$user->lastname;
    }
    if ($hasfirstname) {
        return $user->firstname;
    }
    if ($haslastname) {
        return $user->lastname;
    }
    return '';
}
