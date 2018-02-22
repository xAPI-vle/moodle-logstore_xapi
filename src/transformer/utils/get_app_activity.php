<?php

namespace transformer\utils;

function get_app_activity(array $event) {
    return [
        'id' => $event['app_url'],
        'definition' => [
            'type' => app_type,
            'name' => [
                $event['context_lang'] => $event['app_name'],
            ],
        ],
    ];
}
