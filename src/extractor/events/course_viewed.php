<?php

namespace extractor\events;

use extractor\utils as utils;

function course_viewed(array $config, array $event) {
    $repo = $config['repo'];
    $user = $repo->read_user($event['userid']);
    $site = $repo->read_site();
    $course = $repo->read_course($event['courseid']);
    $source_version = $repo->read_release();

    return [
        'recipe' => 'course_viewed',

        'user_id' => $user->id,
        'user_email' => $user->email,
        'user_url' => $user->url,
        'user_name' => $user->fullname,

        'course_url' => $course->url,
        'course_name' => utils\get_course_name($course),
        'course_type' => utils\get_course_type($course),
        'course_lang' => utils\get_course_lang($course),

        'time' => utils\get_event_timestamp($event),
        
        'app_url' => $site->url,
        'app_name' => utils\get_site_name($site),
    ];
}