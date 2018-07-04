<?php
namespace src\transformer;
defined('MOODLE_INTERNAL') || die();

function handler(array $config, array $events) {
    $eventfunctionmap = get_event_function_map();
    return array_reduce($events, function ($statements, $event) use ($config, $eventfunctionmap) {
        $eventobj = (object) $event;
        $eventname = $eventobj->eventname;
        $eventfunctionname = $eventfunctionmap[$eventname];
        $eventfunction = '\src\transformer\events\\' . $eventfunctionname;
        $eventconfig = array_merge([
            'event_function' => $eventfunction,
        ], $config);
        $eventstatements = $eventfunction($eventconfig, $eventobj);
        return array_merge($statements, $eventstatements);
    }, []);
}
