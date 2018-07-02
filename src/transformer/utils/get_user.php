<?php

namespace src\transformer\utils;

function get_user(array $config, \stdClass $user) {
    $full_name = get_full_name($user);
    if (array_key_exists('sendmbox', $config) && $config['sendmbox'] == true) {
        return [
            'name' => $full_name,
            'mbox' => $user->email,
        ];
    } else {
        return [
            'name' => $full_name,
            'account' => [
                'homePage' => $config['app_url'],
                'name' => strval($user->id),
            ],
        ];
    }
}
