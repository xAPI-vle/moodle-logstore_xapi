<?php

namespace transformer\events\mod_assign;

use transformer\utils as utils;

function assignment_graded(array $config, array $event) {
    $repo = $config['repo'];
    $grade = $repo->read_object($event['objectid'], $event['objecttable']);
    $user = $repo->read_user($grade->userid);
    $course = $repo->read_course($event['courseid']);
    $instructor = $repo->read_user($event['userid']);
    $lang = utils\get_course_lang($course);

    $gradecomment = $repo->read_store_record('assignfeedback_comments', ['assignment' => $grade->assignment, 'grade' => $grade->id])->commenttext;
    $gradeitems = $repo->read_store_record('grade_items', ['itemmodule' => 'assign', 'iteminstance' => $grade->assignment]);

    $scoreraw = (float) ($grade->grade ?: 0);
    $scoremin = (float) ($gradeitems->grademin ?: 0);
    $scoremax = (float) ($gradeitems->grademax ?: 0);
    $scorepass = (float) ($gradeitems->gradepass ?: null);

    $completion = 'unknown';

    if ($scoreraw >= $scorepass) {
        $completion = true;
    }

    // Calculate scaled score as the distance from zero towards the max (or min for negative scores).
    if ($scoreraw >= 0) {
        $scorescaled = $scoreraw / $scoremax;
    } else {
        $scorescaled = $scoreraw / $scoremin;
    }

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'http://adlnet.gov/expapi/verbs/scored',
            'display' => [
                $lang => 'scored'
            ],
        ],
        'object' => utils\get_module_activity($config, $event, $lang),
        'result' => [
            'score' => [
                'raw' => $scoreraw,
                'min' => $scoremin,
                'max' => $scoremax,
                'scaled' => $scorescaled
            ],
            'completion' => $completion,
            'response' => $gradecomment
        ],
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
            'instructor' => utils\get_user($config, $instructor)
        ]
    ]];
}
