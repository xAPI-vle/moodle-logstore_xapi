<?php

namespace transformer\events\core;

use transformer\utils as utils;

function user_loggedout(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->userid);
    $lang = $config['source_lang'];

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'https://brindlewaye.com/xAPITerms/verbs/loggedout/',
            'display' => [
                $lang => 'logged out'
            ],
        ],
        'object' => utils\get_activity\site($config),
        'timestamp' => utils\get_event_timestamp($event),
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => [
                utils\info_extension => utils\get_info($config, $event),
            ],
            'contextActivities' => [
                'category' => [
                    utils\get_activity\source($config)
                ]
            ],
        ]
    ]];
}
