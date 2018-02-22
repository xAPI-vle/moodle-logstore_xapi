<?php

namespace transformer\utils;

function get_course_lang($course) {
    $has_lang = is_null($course->lang) || $course->lang == '';
    return $has_lang ? 'en' : $course->lang;
}
