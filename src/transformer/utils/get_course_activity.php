<?php

namespace transformer\utils;

function get_course_activity($course) {
    $course_name = $course->fullname ?: 'A Moodle course';
    $course_type = xapi_type.$course->type;
    $course_lang = get_course_lang($course);

    return [
        'id' => $course->url,
        'definition' => [
            'type' => $course_type,
            'name' => [
                $course_lang => $course_name,
            ],
        ],
    ];
}
