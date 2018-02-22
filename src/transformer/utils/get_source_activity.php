<?php

namespace transformer\utils;

function get_source_activity(array $config) {
    return [
        'id' => $config['source_url'],
        'definition' => [
            'type' => source_type,
            'name' => [
                $config['source_lang'] => $config[$key.'source_name'],
            ],
        ],
    ];
}
