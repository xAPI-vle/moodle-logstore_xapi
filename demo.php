<?php

namespace src;

require_once(__DIR__ . '/src/autoload.php');
require_once(__DIR__ . '/version.php');

$data = json_decode(file_get_contents(__DIR__.'/tests/core/course_viewed/data.json'));
$event = json_decode(file_get_contents(__DIR__.'/tests/core/course_viewed/event.json'));

$handler_config = [
    'transformer' => [
        'source_url' => 'http://moodle.org',
        'source_name' => 'Moodle',
        'source_version' => '1.0.0',
        'source_lang' => 'en',
        'send_mbox' => false,
        'plugin_url' => 'https://github.com/xAPI-vle/moodle-logstore_xapi',
        'plugin_version' => $plugin->release,
        'repo' => new \src\transformer\repos\TestRepository($data),
        'app_url' => 'http://www.example.org',
    ],
    'loader' => [
        'loader' => 'log',
        'lrs_endpoint' => '',
        'lrs_username' => '',
        'lrs_password' => '',
        'lrs_max_batch_size' => 1,
    ],
];

handler($handler_config, [$event]);
