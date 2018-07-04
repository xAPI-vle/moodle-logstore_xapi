<?php
namespace src\transformer\utils\get_activity;
defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

function site(array $config) {
    $repo = $config['repo'];
    $site = $repo->read_record_by_id('course', 1);
    $sitename = $site->fullname ? $site->fullname : 'A Moodle site';
    $sitelang = utils\get_course_lang($site);

    return [
        'id' => $config['app_url'],
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/site',
            'name' => [
                $sitelang => $sitename,
            ],
        ],
    ];
}
