<?php

namespace src;

require_once(__DIR__ . '/src/autoload.php');
require_once(__DIR__ . '/version.php');

$DB = (object) [];
$CFG = (object) [
    'wwwroot' => 'http://www.example.com',
    'release' => '1.0.0',
];

$events = [[
    'userid' => 1,
    'courseid' => 1,
    'timecreated' => time(),
    'eventname' => '\mod_feedback\event\response_submitted',
    'objectid'=>1,
]];

$handler_config = [
    'transformer' => [
        'source_url' => 'http://moodle.org',
        'source_name' => 'Moodle',
        'source_version' => '1.0.0',
        'source_lang' => 'en',
        'send_mbox' => false,
        'plugin_url' => 'https://github.com/xAPI-vle/moodle-logstore_xapi',
        'plugin_version' => $plugin->release,
        'repo' => new \transformer\FakeRepository($DB, $CFG),
        'wwwroot' => $CFG->wwwroot,
    ],
    'loader' => [
        'loader' => 'log',
        'lrs_endpoint' => '',
        'lrs_username' => '',
        'lrs_password' => '',
        'lrs_max_batch_size' => 1,
    ],
];

handler($handler_config, $events);
