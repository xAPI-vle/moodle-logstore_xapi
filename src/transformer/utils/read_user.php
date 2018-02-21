<?php

namespace transformer\utils;

function read_user(array $event, $key) {
    if (isset($event['sendmbox']) && $event['sendmbox'] == true) {
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
