<?php

namespace extractor\utils;

function get_course_name($course) {
    return $course->fullname ?: 'A Moodle course';
}
