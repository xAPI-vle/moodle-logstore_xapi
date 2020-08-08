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

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . "/enchancement_jisc_skeleton.php");

/**
 * @package    logstore_xapi
 * @author     László Záborski <laszlo.zaborski@learningpool.com>
 * @copyright  2020 Learning Pool Ltd (http://learningpool.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class moveback_failed_statements_test extends enchancement_jisc_skeleton {

    /**
     * General test for checking stores are writeable and readable.
     */
    public function test_general() {
        parent::test_general();
    }

    /**
     * Creating minimum a single course view event to xapi logstore.
     * Using moveback script for moving a single element
     */
    public function test_single_element() {
        global $DB;

        parent::test_single_element();

        $records = $DB->get_records('logstore_xapi_failed_log');
        $this->assertCount($this->generatedxapylog, $records);

        $keys = array_keys($records);

        // Move back elements.
        $mover = new moveback($keys);
        $this->assertTrue($mover->execute());

        $expectedcount = new stdClass();
        $expectedcount->logstore_xapi_log = $this->generatedxapylog;
        $expectedcount->logstore_xapi_failed_log = 0;
        $this->assert_store_tables($expectedcount);
    }

    /**
     * Creating multiple course view events to xapi logstore.
     * Record number depends on $multipletestnumber.
     * Using moveback script for moving multiple elements.
     */
    public function test_multiple_elements() {
        global $DB;

        parent::test_multiple_elements();

        $records = $DB->get_records('logstore_xapi_failed_log');
        $this->assertCount($this->multipletestnumber * $this->generatedxapylog, $records);

        $keys = array_keys($records);

        // Move back elements.
        $mover = new moveback($keys);
        $this->assertTrue($mover->execute());

        $expectedcount = new stdClass();
        $expectedcount->logstore_xapi_log = $this->multipletestnumber * $this->generatedxapylog;
        $expectedcount->logstore_xapi_failed_log = 0;
        $this->assert_store_tables($expectedcount);
    }
}