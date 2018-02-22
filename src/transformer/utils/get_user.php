<?php

namespace transformer\utils;

function get_user(array $config, array $event, $key) {
    if (isset($config['sendmbox']) && $config['sendmbox'] == true) {
        return [
            'name' => $event[$key.'_name'],
            'mbox' => $event[$key.'_email'],
        ];
    } else {
        return [
            'name' => $event[$key.'_name'],
            'account' => [
                'homePage' => $event[$key.'_url'],
                'name' => $event[$key.'_id'],
            ],
        ];
    }
}
