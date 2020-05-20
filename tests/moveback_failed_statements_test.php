<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

use logstore_xapi\log\moveback;
use logstore_xapi\task\emit_task;

defined('MOODLE_INTERNAL') || die();

/**
 * @package    logstore_xapi
 * @author     László Záborski <laszlo.zaborski@learningpool.com>
 * @copyright  2020 Learning Pool Ltd (http://learningpool.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class moveback_failed_statements_test extends advanced_testcase {

    /**
     * Investigate given counts.
     *
     * @param stdClass $counts
     */
    protected function assert_store_tables(stdClass $counts) {
        global $DB;

        if (isset($counts->logstore_standard_log)) {
            $logs = $DB->get_records('logstore_standard_log', array(), 'id ASC');
            $this->assertCount($counts->logstore_standard_log, $logs);
        }

        if (isset($counts->logstore_xapi_log)) {
            $logs = $DB->get_records('logstore_xapi_log', array(), 'id ASC');
            $this->assertCount($counts->logstore_xapi_log, $logs);
        }

        if (isset($counts->logstore_xapi_failed_log)) {
            $logs = $DB->get_records('logstore_xapi_failed_log', array(), 'id ASC');
            $this->assertCount($counts->logstore_xapi_failed_log, $logs);
        }
    }

    /**
     * Generate log data.
     *
     * @param testing_data_generator $generator
     * @return bool|int generated record id or false
     */
    protected function add_test_log_data(testing_data_generator $generator) {
        global $DB;

        $user = $generator->create_user();
        $course = $generator->create_course();
        $context = context_course::instance($course->id);

        $record = (object)array(
            'eventname' => '\core\event\course_viewed',
            'component' => 'core',
            'action' => 'viewed',
            'target' => 'course',
            'crud' => 'r',
            'edulevel' => 2,
            'contextid' => $context->id,
            'contextlevel' => $context->contextlevel,
            'contextinstanceid' => $context->instanceid,
            'userid' => $user->id,
            'timecreated' => time(),
        );
        $record->logstorestandardlogid = $DB->insert_record('logstore_standard_log', $record);

        return $DB->insert_record('logstore_xapi_log', $record);
    }

    public function test_general() {
        $this->resetAfterTest();
        $this->setAdminUser();

        // Test all plugins are disabled by this command.
        set_config('enabled_stores', '', 'tool_log');

        $manager = get_log_manager(true);
        $stores = $manager->get_readers();

        $this->assertCount(0, $stores);

        // Enable both log stores.
        set_config('enabled_stores', 'logstore_standard,logstore_xapi', 'tool_log');
        set_config('buffersize', 0, 'logstore_standard');
        set_config('logguests', 1, 'logstore_standard');
        set_config('buffersize', 0, 'logstore_xapi');
        set_config('logguests', 1, 'logstore_xapi');

        // We have only one readers.
        $manager = get_log_manager(true);
        $stores = $manager->get_readers();
        $this->assertCount(1, $stores);

        // But both are writter.
        $store = new logstore_standard\log\store($manager);
        $this->assertInstanceOf('logstore_standard\log\store', $store);
        $this->assertInstanceOf('tool_log\log\writer', $store);
        $this->assertTrue($store->is_logging());

        $store = new logstore_xapi\log\store($manager);
        $this->assertInstanceOf('logstore_xapi\log\store', $store);
        $this->assertInstanceOf('tool_log\log\writer', $store);
        $this->assertTrue($store->is_logging());

        // We don't have records in store tables.
        $expectedcount = new stdClass();
        $expectedcount->logstore_standard_log = 0;
        $expectedcount->logstore_xapi_log = 0;
        $expectedcount->logstore_xapi_failed_log = 0;
        $this->assert_store_tables($expectedcount);

        $generator = $this->getDataGenerator();
        $this->add_test_log_data($generator);

        $expectedcount->logstore_standard_log = 11;
        $expectedcount->logstore_xapi_log = 1;
        $this->assert_store_tables($expectedcount);

    }

    public function test_single_element() {
        global $DB;

        $this->resetAfterTest();
        $this->setAdminUser();

        // Enable log stores.
        set_config('enabled_stores', 'logstore_standard,logstore_xapi', 'tool_log');
        set_config('buffersize', 0, 'logstore_standard');
        set_config('logguests', 1, 'logstore_standard');
        set_config('buffersize', 0, 'logstore_xapi');
        set_config('logguests', 1, 'logstore_xapi');

        $expectedcount = new stdClass();
        $expectedcount->logstore_standard_log = 0;
        $expectedcount->logstore_xapi_log = 0;
        $expectedcount->logstore_xapi_failed_log = 0;
        $this->assert_store_tables($expectedcount);

        $generator = $this->getDataGenerator();
        $this->add_test_log_data($generator);

        $expectedcount->logstore_standard_log = 11;
        $expectedcount->logstore_xapi_log = 1;
        $expectedcount->logstore_xapi_failed_log = 0;
        $this->assert_store_tables($expectedcount);

        // Run emit_task silently.
        set_debugging(DEBUG_NONE);
        $task = new emit_task();
        ob_start();
        $task->execute();
        ob_end_clean();

        unset($expectedcount->logstore_standard_log);
        $expectedcount->logstore_xapi_log = 0;
        $expectedcount->logstore_xapi_failed_log = 1;
        $this->assert_store_tables($expectedcount);

        $records = $DB->get_records('logstore_xapi_failed_log');
        $this->assertCount(1, $records);

        $keys = array_keys($records);

        // Move back elements
        $mover = new moveback($keys);
        $this->assertTrue($mover->execute());

        $expectedcount->logstore_xapi_log = 1;
        $expectedcount->logstore_xapi_failed_log = 0;
        $this->assert_store_tables($expectedcount);
    }

    public function test_multiple_elements() {
        global $DB;

        $this->resetAfterTest();
        $this->setAdminUser();

        // Enable log stores.
        set_config('enabled_stores', 'logstore_standard,logstore_xapi', 'tool_log');
        set_config('buffersize', 0, 'logstore_standard');
        set_config('logguests', 1, 'logstore_standard');
        set_config('buffersize', 0, 'logstore_xapi');
        set_config('logguests', 1, 'logstore_xapi');

        $expectedcount = new stdClass();
        $expectedcount->logstore_xapi_log = 0;
        $expectedcount->logstore_xapi_failed_log = 0;
        $this->assert_store_tables($expectedcount);

        $generator = $this->getDataGenerator();

        $imax = 5;

        for ($i = 1; $i <= $imax; $i++) {
            $this->add_test_log_data($generator);
        }

        $expectedcount->logstore_xapi_log = $imax;
        $expectedcount->logstore_xapi_failed_log = 0;
        $this->assert_store_tables($expectedcount);

        // Run emit_task silently.
        set_debugging(DEBUG_NONE);
        $task = new emit_task();
        ob_start();
        $task->execute();
        ob_end_clean();

        $expectedcount->logstore_xapi_log = 0;
        $expectedcount->logstore_xapi_failed_log = $imax;
        $this->assert_store_tables($expectedcount);

        $records = $DB->get_records('logstore_xapi_failed_log');
        $this->assertCount($imax, $records);

        $keys = array_keys($records);

        // Move back elements
        $mover = new moveback($keys);
        $this->assertTrue($mover->execute());

        $expectedcount->logstore_xapi_log = $imax;
        $expectedcount->logstore_xapi_failed_log = 0;
        $this->assert_store_tables($expectedcount);
    }
}