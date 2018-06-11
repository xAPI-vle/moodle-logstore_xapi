<?php

namespace src\transformer;

function handler(array $config, array $events) {
    $event_function_map = get_event_function_map();
    return array_reduce($events, function ($statements, $event) use ($config, $event_function_map) {
        $event_obj = (object) $event;
        $event_name = $event_obj->eventname;
        $event_function_name = $event_function_map[$event_name];
        $event_function = '\src\transformer\events\\' . $event_function_name;
        $event_config = array_merge([
            'event_function' => $event_function,
        ], $config);
        $event_statements = $event_function($event_config, $event_obj);
        return array_merge($statements, $event_statements);
    }, []);
}