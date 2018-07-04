<?php

defined('MOODLE_INTERNAL') || die();

$plugin = isset($plugin) && is_object($plugin) ? $plugin : new StdClass();
$plugin->component = 'logstore_xapi';
$plugin->version = 2018061100;
$plugin->release = '0.0.0-development';
$plugin->requires = 2014111000;
$plugin->maturity = MATURITY_ALPHA;
