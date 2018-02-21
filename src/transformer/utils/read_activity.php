<?php

namespace transformer\utils;

function read_activity(array $event, $key) {
    $activity = [
        'id' => $event[$key.'_url'],
        'definition' => [
            'type' => $event[$key.'_type'],
            'name' => [
                $event['context_lang'] => $event[$key.'_name'],
            ],
            'description' => [
                $event['context_lang'] => $event[$key.'_description'],
            ],
        ],
    ];

    if (isset($event[$key.'_ext']) && isset($event[$key.'_ext_key'])) {
        $activity['definition']['extensions'] = [];
        $activity['definition']['extensions'][$event[$key.'_ext_key']] = $event[$key.'_ext'];
    }

    return $activity;
}
