<?php

namespace src\transformer\events\mod_scorm;

use src\transformer\utils as utils;

function scoreraw_submitted(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->userid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $lang = utils\get_course_lang($course);

    $unserialized_cmi = unserialize($event->other);
    $attempt = $unserialized_cmi['attemptid'];
    $scorm_scoes_tracks = $repo->read_records('scorm_scoes_track', [
        'userid' => $userid,
        'scormid' => $event->objectid,
        'scoid' => $event->contextinstanceid,
        'attempt' => $unserialized_cmi['attemptid']
    ]);
    $raw_score = $unserialized_cmi['cmivalue'];

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => utils\get_scorm_verb($scorm_scoes_tracks, $lang),
        'object' => utils\get_activity\event_module($config, $event, $lang),
        'timestamp' => utils\get_event_timestamp($event),
        'result' => utils\get_scorm_result($scorm_scoes_tracks, $raw_score),
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => [
                utils\info_extension => utils\get_info($config, $event),
            ],
            'contextActivities' => [
                'grouping' => [
                    utils\get_activity\site($config),
                    utils\get_activity\course($config, $course),
                ],
                'category' => [
                    utils\get_activity\source($config),
                ]
            ],
        ]
    ]];
}