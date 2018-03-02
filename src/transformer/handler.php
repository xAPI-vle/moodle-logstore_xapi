<?php

namespace transformer;

function handler(array $config, array $events) {
    $event_function_map = [
        '\core\event\course_completed' => 'core\course_completed',
        '\core\event\course_viewed' => 'core\course_viewed',
        '\core\event\user_created' => 'core\user_created',
        '\core\event\user_enrolment_created' => 'core\user_enrolment_created',
        '\core\event\user_loggedin' => 'core\user_loggedin',
        '\core\event\user_loggedout' => 'core\user_loggedout',
        '\mod_scorm\event\course_module_viewed' => 'mod_scorm\course_module_viewed',
        '\mod_scorm\event\sco_launched' => 'mod_scorm\sco_launched',
        '\mod_scorm\event\scoreraw_submitted' => 'mod_scorm\scoreraw_submitted',
        '\mod_scorm\event\status_submitted' => 'mod_scorm\status_submitted',
    ];
    return array_reduce($events, function ($statements, $event) use ($config, $event_function_map) {
        $event_name = $event['eventname'];
        $event_function_name = $event_function_map[$event_name];
        $event_function = '\transformer\events\\' . $event_function_name;
        $event_statements = $event_function($config, $event);
        return array_merge($statements, $event_statements);
    }, []);
}