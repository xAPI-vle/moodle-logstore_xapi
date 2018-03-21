<?php

namespace transformer\utils\get_activity;

use transformer\utils as utils;

function course(array $config, \stdClass $course) {
    $course_name = $course->fullname ?: 'A Moodle course';
    $course_lang = utils\get_course_lang($course);

    return [
        'id' => utils\get_activity_url($config, 'course', $course->id),
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/lms/course',
            'name' => [
                $course_lang => $course_name,
            ],
        ],
    ];
}
