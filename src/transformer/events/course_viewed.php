<?php

namespace transformer\events;

use transformer\utils as utils;

function course_viewed($event) {
    return [
        'actor' => utils\read_user($event, 'user'),
        'verb' => [
            'id' => 'http://id.tincanapi.com/verb/viewed',
            'display' => 'viewed',
        ],
        'object' => utils\read_activity($event, 'course'),
        'timestamp' => $event['time'],
        'context' => [
            'platform' => $event['context_platform'],
            'language' => $event['context_lang'],
            'extensions' => [
                utils\info_extension => $event['context_info'],
            ],
            'contextActivities' => [
                'grouping' => [
                    utils\read_activity($event, 'app')
                ],
                'category' => [
                    utils\read_activity($event, 'source')
                ]
            ],
        ]
    ];
}
