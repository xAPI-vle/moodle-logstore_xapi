<?php
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/src/autoload.php');

if ($hassiteconfig) {
    // Endpoint.
    $settings->add(new admin_setting_configtext('logstore_xapi/endpoint',
        get_string('endpoint', 'logstore_xapi'), '',
        'http://example.com/endpoint/location/', PARAM_URL));
    // Username.
    $settings->add(new admin_setting_configtext('logstore_xapi/username',
        get_string('username', 'logstore_xapi'), '', 'username', PARAM_TEXT));
    // Key or password.
    $settings->add(new admin_setting_configtext('logstore_xapi/password',
        get_string('password', 'logstore_xapi'), '', 'password', PARAM_TEXT));

    // Switch background batch mode on.
    $settings->add(new admin_setting_configcheckbox('logstore_xapi/backgroundmode',
        get_string('backgroundmode', 'logstore_xapi'),
        get_string('backgroundmode_desc', 'logstore_xapi'), 0));

    $settings->add(new admin_setting_configtext('logstore_xapi/maxbatchsize',
        get_string('maxbatchsize', 'logstore_xapi'),
        get_string('maxbatchsize_desc', 'logstore_xapi'), 30, PARAM_INT));

    $settings->add(new admin_setting_configcheckbox('logstore_xapi/mbox',
        get_string('mbox', 'logstore_xapi'),
        get_string('mbox_desc', 'logstore_xapi'), 0));

    // Filters.
    $settings->add(new admin_setting_heading('filters',
        get_string('filters', 'logstore_xapi'),
        get_string('filters_help', 'logstore_xapi')));

    $settings->add(new admin_setting_configcheckbox('logstore_xapi/logguests',
        get_string('logguests', 'logstore_xapi'), '', '0'));

    $menuroutes = [];
    $eventfunctionmap = \src\transformer\get_event_function_map();
    foreach (array_keys($eventfunctionmap) as $eventname) {
        $menuroutes[$eventname] = $eventname;
    }

    $settings->add(new admin_setting_configmulticheckbox('logstore_xapi/routes',
        get_string('routes', 'logstore_xapi'), '', $menuroutes, $menuroutes));

}
