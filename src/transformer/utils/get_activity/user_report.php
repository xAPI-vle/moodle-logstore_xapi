<?php

namespace src\transformer\utils\get_activity;

use src\transformer\utils as utils;

function user_report(array $config, \stdClass $user, \stdClass $course) {
    $course_lang = utils\get_course_lang($course);

    return [
        'id' => $config['app_url'].'/mod/forum/user.php?id='.$user->id.'&course='.$course->id,
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/user-profile',
            'name' => [
                $course_lang => 'forum posts of '.utils\get_full_name($user),
            ],
            'extensions' => [
                'https://moodle.org/xapi/extensions/user_id' => $user->id,
                'https://moodle.org/xapi/extensions/course_id' => $course->id,
            ],
        ],
    ];
}
