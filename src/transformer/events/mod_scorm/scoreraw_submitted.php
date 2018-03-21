<?php

namespace transformer\events\mod_scorm;

use transformer\utils as utils;

function scoreraw_submitted(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->userid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $lang = utils\get_course_lang($course);

    //TODO: move to utils
    $cmiunserialized = unserialize($event->other);
    $scoid = $event->contextinstanceid;
    $scormid = $event->objectid;
    $attempt = $cmiunserialized['attemptid'];
    $scormscoestrack = utils\get_scorm_scoes_track($config, $event->userid, $scormid, $scoid, $attempt);
    $scormscoes = $repo->read_record_by_id('scorm_scoes', $scoid);

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => utils\get_scorm_verb($scormscoestrack['status'], $lang),
        'object' => utils\get_activity\module($config, $event, $lang),
        'result' => utils\get_scorm_result($scormscoestrack, $cmiunserialized),
        'timestamp' => utils\get_event_timestamp($event),
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