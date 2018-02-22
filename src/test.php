<?php

namespace src;

require_once(__DIR__ . '/autoload.php');

$repo = new \extractor\FakeRepository(
    (object) [],
    (object) [
        'wwwroot' => 'http://www.example.com',
        'release' => '1.0.0',
    ]
);

$event = \extractor\events\course_viewed([
    'repo' => $repo,
], []);
echo(json_encode($event, JSON_PRETTY_PRINT));
echo("\n");

$statement = \transformer\events\course_viewed([
    'source_url' => 'http://moodle.org',
    'source_name' => 'Moodle',
    'source_version' => '1.0.0',
    'source_lang' => 'en',
    'send_mbox' => false,
    'plugin_url' => 'http://www.example.org/plugin',
    'plugin_version' => '1.0.0',
], $event);
echo(json_encode($statement, JSON_PRETTY_PRINT));
echo("\n");
