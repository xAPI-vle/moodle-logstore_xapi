<?php

defined('MOODLE_INTERNAL') || die();

function xmldb_logstore_xapi_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2015081001) {

        // Define table logstore_xapi_log to be created.
        $table = new xmldb_table('logstore_xapi_log');

        // Adding fields to table logstore_xapi_log.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('eventname', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('component', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('action', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('target', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('objecttable', XMLDB_TYPE_CHAR, '50', null, null, null, null);
        $table->add_field('objectid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('crud', XMLDB_TYPE_CHAR, '1', null, XMLDB_NOTNULL, null, null);
        $table->add_field('edulevel', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null);
        $table->add_field('contextid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('contextlevel', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('contextinstanceid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('relateduserid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('anonymous', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('other', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('origin', XMLDB_TYPE_CHAR, '10', null, null, null, null);
        $table->add_field('ip', XMLDB_TYPE_CHAR, '45', null, null, null, null);
        $table->add_field('realuserid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table logstore_xapi_log.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Adding indexes to table logstore_xapi_log.
        $table->add_index('timecreated', XMLDB_INDEX_NOTUNIQUE, array('timecreated'));
        $table->add_index('course-time', XMLDB_INDEX_NOTUNIQUE, array('courseid', 'anonymous', 'timecreated'));
        $table->add_index('user-module', XMLDB_INDEX_NOTUNIQUE, array(
            'userid',
            'contextlevel',
            'contextinstanceid',
            'crud',
            'edulevel',
            'timecreated'
        ));

        // Conditionally launch create table for logstore_xapi_log.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Xapi savepoint reached.
        upgrade_plugin_savepoint(true, 2015081001, 'logstore', 'xapi');
    }

    return true;
}
