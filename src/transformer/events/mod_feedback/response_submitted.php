<?php

namespace transformer\events\mod_feedback;

use transformer\utils as utils;

function response_submitted(array $config, array $event) {
    $repo = $config['repo'];
    $user = $repo->read_user($event['userid']);
    $course = $repo->read_course($event['courseid']);
    $lang = utils\get_course_lang($course);
    $feedback_completed = $repo->read_store_record('feedback_completed', [
        'id' => $event['objectid'],
    ]);
    $feedback_values = $repo->read_store_records('feedback_value', [
        'completed' => $feedback_completed->id,
    ]);
    $feedback = $repo->read_store_record('feedback', [
        'id' => $feedback_completed->feedback,
    ]);
    $statements = [];

    foreach ($feedback_values as $feedback_value) {
        $feedback_item = $repo->read_store_record('feedback_item', [
            'id' => $feedback_value->item
        ]);
        $statements[] = [
            'actor' => utils\get_user($config, $user),
            'verb' => [
                'id' => 'http://id.tincanapi.com/verb/submitted',
                'display' => [
                    $lang => 'submitted'
                ],
            ],
            'object' => [
                'id' => utils\get_activity_id($config, 'feedback_item', $feedback_item->id),
                'definition' => [
                    'type' => utils\xapi_type.'feedback_item',
                    'name' => [
                        $lang => $feedback_item->name,
                    ],
                ],
            ],
            'result' => [
                'response'=> $feedback_value->value,
            ],
            'timestamp' => utils\get_event_timestamp($event),
            'context' => [
                'platform' => $config['source_name'],
                'language' => $lang,
                'extensions' => [
                    utils\info_extension => utils\get_info($config, $event),
                ],
                'contextActivities' => [
                    'parent' => [
                        [
                            'id' => utils\get_activity_id($config, 'feedback', $feedback->id),
                            'definition' => [
                                'type' => utils\xapi_type.'feedback',
                                'name' => [
                                    $lang => $feedback->name,
                                ],
                            ],
                        ]
                    ],
                    'grouping' => [
                        utils\get_course_activity($course)
                    ],
                    'category' => [
                        utils\get_source_activity($config)
                    ]
                ],
            ]
        ];
    }

    return $statements;
}