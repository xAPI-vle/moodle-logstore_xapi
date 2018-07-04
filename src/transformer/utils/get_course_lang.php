<?php
namespace src\transformer\utils;
defined('MOODLE_INTERNAL') || die();

function get_course_lang($course) {
    $haslang = is_null($course->lang) || $course->lang == '';
    return $haslang ? 'en' : $course->lang;
}
