<?php

namespace src\transformer\events\mod_scorm;

defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

function scoreraw_submitted(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->userid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $scorm = $repo->read_record_by_id('scorm', $event->objectid);
    $lang = utils\get_course_lang($course);

    $unserializedcmi = unserialize($event->other);
    $attempt = $unserializedcmi['attemptid'];
    $scormscoestracks = $repo->read_records('scorm_scoes_track', [
        'userid' => $user->id,
        'scormid' => $event->objectid,
        'scoid' => $event->contextinstanceid,
        'attempt' => $unserializedcmi['attemptid']
    ]);
    $rawscore = floatval($unserializedcmi['cmivalue']);

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => utils\get_scorm_verb($scormscoestracks, $lang),
        'object' => utils\get_activity\module($config, 'scorm', $scorm, $lang),
        'timestamp' => utils\get_event_timestamp($event),
        'result' => utils\get_scorm_result($scormscoestracks, $rawscore),
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => [
                utils\INFO_EXTENSION => utils\get_info($config, $event),
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