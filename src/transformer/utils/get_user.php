<?php
namespace src\transformer\utils;
defined('MOODLE_INTERNAL') || die();

function get_user(array $config, \stdClass $user) {
    $fullname = get_full_name($user);
    if (array_key_exists('sendmbox', $config) && $config['sendmbox'] == true) {
        return [
            'name' => $fullname,
            'mbox' => $user->email,
        ];
    } else {
        return [
            'name' => $fullname,
            'account' => [
                'homePage' => $config['app_url'],
                'name' => strval($user->id),
            ],
        ];
    }
}
