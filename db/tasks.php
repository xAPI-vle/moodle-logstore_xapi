<?php
defined('MOODLE_INTERNAL') || die();

$tasks = array(
    array(
        'classname' => '\logstore_xapi\task\emit_task',
        'blocking' => 0,
        'minute' => '*/1',
        'hour' => '*',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    ),
);