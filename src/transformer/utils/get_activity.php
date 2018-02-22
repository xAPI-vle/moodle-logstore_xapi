<?php

namespace transformer\utils;

function get_activity(array $event, $key) {
    return [
        'id' => $event[$key.'_url'],
        'definition' => [
            'type' => $event[$key.'_type'],
            'name' => [
                $event['context_lang'] => $event[$key.'_name'],
            ],
        ],
    ];
}
