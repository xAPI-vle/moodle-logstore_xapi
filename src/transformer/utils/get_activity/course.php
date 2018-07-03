<?php

namespace src\transformer\utils\get_activity;

use src\transformer\utils as utils;

function course(array $config, \stdClass $course) {
    $course_name = $course->fullname ? $course->fullname : 'A Moodle course';
    $course_lang = utils\get_course_lang($course);

    return [
        'id' => $config['app_url'].'/course/view.php?id='.$course->id,
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/lms/course',
            'name' => [
                $course_lang => $course_name,
            ],
        ],
    ];
}
