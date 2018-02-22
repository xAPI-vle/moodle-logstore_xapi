<?php

namespace transformer\utils;

function get_site_activity(array $config, array $event, $lang) {
    return [
        'id' => $event['app_url'],
        'definition' => [
            'type' => app_type,
            'name' => [
                $lang => $event['app_name'],
            ],
        ],
    ];
}
