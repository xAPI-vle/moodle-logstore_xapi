<?php

namespace transformer\events;

function course_viewed($event) {
    return [
        'actor' => \transformer\utils\read_user($event, 'user'),
        'verb' => [
            'id' => 'http://id.tincanapi.com/verb/viewed',
            'display' => 'viewed',
        ],
        'object' => \transformer\utils\read_activity($event, 'course'),
        'timestamp' => $event['time'],
        'context' => [
            'platform' => $event['context_platform'],
            'language' => $event['context_lang'],
            'extensions' => [
                \transformer\utils\info_extension => $event['context_info'],
            ],
            'contextActivities' => [
                'grouping' => [
                    \transformer\utils\read_activity($event, 'app')
                ],
                'category' => [
                    \transformer\utils\read_activity($event, 'source')
                ]
            ],
        ]
    ];
}
