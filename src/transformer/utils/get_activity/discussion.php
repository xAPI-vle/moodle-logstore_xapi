<?php

namespace src\transformer\utils\get_activity;
defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

function discussion(array $config, \stdClass $discussion) {
    $lang = $config['source_lang'];
    $appurl = $config['app_url'];
    $discussionurl = $appurl . '/mod/forum/discuss.php?d=' . $discussion->id;

    return [
        'id' => $discussionurl,
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/discussion',
            'name' => [
                $lang => $discussion->name,
            ],
        ],
    ];
}
