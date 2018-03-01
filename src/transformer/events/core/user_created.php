<?php

namespace transformer\events\core;

use transformer\utils as utils;

function user_created(array $config, array $event) {
    $repo = $config['repo'];
    $user = $repo->read_user($event['userid']);
    $site = $repo->read_site();
    $lang = $config['source_lang'];

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'http://adlnet.gov/expapi/verbs/registered',
            'display' => [
                $lang => 'registered'
            ],
        ],
        'object' => utils\get_site_activity($config, $site, $lang),
        'timestamp' => utils\get_event_timestamp($event),
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => [
                utils\info_extension => utils\get_info($config),
            ],
        ]
    ]];
}
