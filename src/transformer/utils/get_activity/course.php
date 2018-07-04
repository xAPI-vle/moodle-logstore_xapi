<?php
namespace src\transformer\utils\get_activity;
defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

function course(array $config, \stdClass $course) {
    $coursename = $course->fullname ? $course->fullname : 'A Moodle course';
    $courselang = utils\get_course_lang($course);

    return [
        'id' => $config['app_url'].'/course/view.php?id='.$course->id,
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/lms/course',
            'name' => [
                $courselang => $coursename,
            ],
        ],
    ];
}
