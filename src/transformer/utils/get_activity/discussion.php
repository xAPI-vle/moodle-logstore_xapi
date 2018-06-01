<?php

namespace src\transformer\utils\get_activity;

use src\transformer\utils as utils;

function discussion(array $config, \stdClass $discussion) {
    $lang = $config['source_lang'];
    $app_url = $config['app_url'];
    $discussion_url = $app_url . '/mod/forum/discuss.php?d=' . $discussion->id;

    return [
        'id' => $discussion_url,
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/discussion',
            'name' => [
                $lang => $discussion->name,
            ],
        ],
    ];
}
