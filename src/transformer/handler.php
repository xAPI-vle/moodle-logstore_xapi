<?php

namespace transformer;

function handler(array $config, array $events) {
    $event_function_map = [
        '\core\event\course_viewed' => 'course_viewed',
    ];
    return array_reduce($events, function ($statements, $event) use ($config, $event_function_map) {
        $event_name = $event['eventname'];
        $event_function_name = $event_function_map[$event_name];
        $event_function = '\transformer\events\\' . $event_function_name;
        $event_statements = $event_function($config, $event);
        return array_merge($statements, $event_statements);
    }, []);
}