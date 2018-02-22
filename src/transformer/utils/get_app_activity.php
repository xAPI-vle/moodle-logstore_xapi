<?php

namespace transformer\utils;

function get_app_activity(array $event) {
    $key = 'app';
    return [
        'id' => $event[$key.'_url'],
        'definition' => [
            'type' => app_type,
            'name' => [
                $event['context_lang'] => $event[$key.'_name'],
            ],
        ],
    ];
}
