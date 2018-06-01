<?php

namespace src\transformer\utils\get_activity;

function source(array $config) {
    return [
        'id' => $config['source_url'],
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/source',
            'name' => [
                $config['source_lang'] => $config['source_name'],
            ],
        ],
    ];
}
