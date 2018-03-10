<?php

namespace transformer\events\mod_scorm;

use transformer\utils as utils;

function scoreraw_submitted(array $config, array $event) {
    $repo = $config['repo'];
    $user = $repo->read_user($event['userid']);
    $course = $repo->read_course($event['courseid']);
    $lang = utils\get_course_lang($course);

    //TODO: move to utils
    $cmiunserialized = unserialize($event['other']);
    $scoid = $event['contextinstanceid'];
    $scormid = $event['objectid'];
    $attempt = $cmiunserialized['attemptid'];
    $scormscoestrack = utils\get_scorm_scoes_track($config, $event['userid'], $scormid, $scoid, $attempt);
    $scormscoes = $repo->read_object($scoid, 'scorm_scoes');

return [[
        'actor' => utils\get_user($config, $user),
        'verb' => utils\get_scorm_verb($scormscoestrack['status'], $lang),
        'object' => utils\get_module_activity($config, $event, $lang),
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
                    utils\get_course_activity($course)
                ],
                'category' => [
                    utils\get_source_activity($config)
                ]
            ],
        ]
    ]];
}