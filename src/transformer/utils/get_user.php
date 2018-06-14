<?php

namespace src\transformer\utils;

function get_user(array $config,  \stdClass $user) {
    $full_name = get_full_name($user);
    if ($config['sendmbox'] == true) {
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
