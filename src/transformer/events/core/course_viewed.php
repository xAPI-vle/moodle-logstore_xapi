<?php

namespace transformer\events\core;

use transformer\utils as utils;

function course_viewed(array $config, array $event) {
    $repo = $config['repo'];
    $user = $repo->read_user($event['userid']);
    $site = $repo->read_site();
    $course = $repo->read_course($event['courseid']);
    $lang = utils\get_course_lang($course);

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'http://id.tincanapi.com/verb/viewed',
            'display' => [
                $lang => 'viewed'
            ],
        ],
        'object' => utils\get_course_activity($course),
        'timestamp' => utils\get_event_timestamp($event),
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => [
                utils\info_extension => utils\get_info($config, $event),
            ],
            'contextActivities' => [
                'grouping' => [
                    utils\get_site_activity($config, $site, $lang)
                ],
                'category' => [
                    utils\get_source_activity($config)
                ]
            ],
        ]
    ]];
}
