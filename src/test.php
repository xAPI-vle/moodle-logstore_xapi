<?php

namespace src;

require_once(__DIR__ . '/autoload.php');

$repo = new \transformer\FakeRepository(
    (object) [],
    (object) [
        'wwwroot' => 'http://www.example.com',
        'release' => '1.0.0',
    ]
);

$events = [[
    'userid' => 1,
    'courseid' => 1,
    'timecreated' => time(),
    'eventname' => '\core\event\course_viewed',
]];

$transformer_config = [
    'source_url' => 'http://moodle.org',
    'source_name' => 'Moodle',
    'source_version' => '1.0.0',
    'source_lang' => 'en',
    'send_mbox' => false,
    'plugin_url' => 'http://www.example.org/plugin',
    'plugin_version' => '1.0.0',
    'repo' => $repo,
];

$loader_config = [
    'loader' => 'log',
];

$handler_config = [
    'loader' => $loader_config,
    'transformer' => $transformer_config,
];

handler($handler_config, $events);
