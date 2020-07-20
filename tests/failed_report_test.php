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

defined('MOODLE_INTERNAL') || die();

/**
 * @package    logstore_xapi
 * @author     László Záborski <laszlo.zaborski@learningpool.com>
 * @copyright  2020 Learning Pool Ltd (http://learningpool.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class failed_report_test extends moveback_failed_statements_test {

    public function test_general() {
        parent::test_general();
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

        tool_logstore_xapi_reportfilter_form::mock_submit(array());
    }
}