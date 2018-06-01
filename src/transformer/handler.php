<?php

namespace src\transformer;

function handler(array $config, array $events) {
    $event_function_map = get_event_function_map();
    return array_reduce($events, function ($statements, $event) use ($config, $event_function_map) {
        $event_name = $event->eventname;
        $event_function_name = $event_function_map[$event_name];
        $event_function = '\src\transformer\events\\' . $event_function_name;
        $event_config = array_merge($config, [
            'event_function' => $event_function,
        ]);
        $event_statements = $event_function($event_config, $event);
        return array_merge($statements, $event_statements);
    }, []);
}