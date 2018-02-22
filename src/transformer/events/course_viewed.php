<?php

namespace transformer\events;

use transformer\utils as utils;

function course_viewed(array $config, array $event) {
    return [
        'actor' => utils\get_user($config, $event, 'user'),
        'verb' => [
            'id' => 'http://id.tincanapi.com/verb/viewed',
            'display' => 'viewed',
        ],
        'object' => utils\get_activity($event, 'course'),
        'timestamp' => $event['time'],
        'context' => [
            'platform' => $config['source_name'],
            'language' => $event['course_lang'],
            'extensions' => [
                utils\info_extension => [
                    $config['source_url'] => $config['source_version'],
                    $config['plugin_url'] => $config['plugin_version'],
                ],
            ],
            'contextActivities' => [
                'grouping' => [
                    utils\get_app_activity($event)
                ],
                'category' => [
                    utils\get_source_activity($config)
                ]
            ],
        ]
    ];
}
