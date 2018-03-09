<?php

namespace transformer\events\mod_forum;

use transformer\utils as utils;

function discussion_viewed(array $config, array $event) {
    $repo = $config['repo'];
    $user = $repo->read_user($event['userid']);
    $course = $repo->read_course($event['courseid']);
    $lang = utils\get_course_lang($course);
    $site = $repo->read_site();
    $discussionid = $event['objectid'];
    $discussion = $repo->read_object($discussionid, 'forum_discussions');
    $discussionurl = $site . '/mod/forum/discuss.php?d=' . $discussionid;

    return[[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'http://id.tincanapi.com/verb/viewed',
            'display' => [
                $lang => 'viewed'
            ],
        ],
        'object' => [
            'id' => $discussionurl,
            'definition' => [
                'type' => 'http://id.tincanapi.com/activitytype/discussion',
                'name' => [
                    $lang => $discussion->name,
                ],
                'description' => [
                    $lang => 'A Moodle discussion.',
                ],
            ],
        ],
        'timestamp' => utils\get_event_timestamp($event),
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => [
                utils\info_extension => utils\get_info($config, $event),
            ],
            'contextActivities' => [
                'grouping' => [
                    utils\get_module_activity($config, $event, $lang)
                ],
                'category' => [
                    utils\get_source_activity($config)
                ]
            ],
        ]
    ]];
}
