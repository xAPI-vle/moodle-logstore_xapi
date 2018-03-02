<?php

namespace transformer\utils;

function get_scorm_object($event){

    return [
        'id' => $event['module_url'],
        'definition' => [
            'type' => $event['scorm_scoes_type'],
            'name' => [
                $event['context_lang'] => $event['scorm_scoes_name'],
            ],
            'description' => [
                $event['context_lang'] => $event['scorm_scoes_description'],
            ],
        ],
    ];
}