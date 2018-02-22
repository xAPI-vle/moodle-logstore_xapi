<?php

namespace transformer\utils;

function get_site_activity(array $config, $site, $lang) {
    $site_name = $site->fullname ?: 'A Moodle site';
    $site_lang = get_course_lang($site);

    return [
        'id' => $site->url,
        'definition' => [
            'type' => app_type,
            'name' => [
                $site_lang => $site_name,
            ],
        ],
    ];
}
