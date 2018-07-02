<?php

namespace src\transformer\utils\get_activity;

use src\transformer\utils as utils;

function site(array $config) {
    $repo = $config['repo'];
    $site = $repo->read_record_by_id('course', 1);
    $site_name = $site->fullname ? $site->fullname : 'A Moodle site';
    $site_lang = utils\get_course_lang($site);

    return [
        'id' => $config['app_url'],
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/site',
            'name' => [
                $site_lang => $site_name,
            ],
        ],
    ];
}
