<?php

namespace extractor\utils;

function get_course_type($course) {
    return xapi_type.$course->type;
}
