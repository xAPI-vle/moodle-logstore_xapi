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

namespace logstore_xapi\mod_lesson\question_viewed;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/admin/tool/log/store/xapi/tests/xapi_test_case.php');

/**
 * Unit test for mod_lesson lesson question (short answer) page viewed.
 *
 * @package   logstore_xapi
 * @copyright Cliff Casey <cliff@yetanalytics.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class shortanswer_test extends \logstore_xapi\xapi_test_case {

    /**
     * Retrieve the directory of the unit test.
     *
     * @return string
     */
    protected function get_test_dir() {
        return __DIR__;
    }

    /**
     * Retrieve the plugin type being tested.
     *
     * @return string
     */
    protected function get_plugin_type() {
        return "mod";
    }

    /**
     * Retrieve the plugin name being tested.
     *
     * @return string
     */
    protected function get_plugin_name() {
        return "glossary";
    }

    /**
     * Appease auto-detecting of test cases. xapi_test_define('has default test cases.
     *
     * @covers ::question_viewed
     * @return void
     */
    public function test_init() {
        if (!defined('LESSON_PAGE_SHORTANSWER')) {
            define('LESSON_PAGE_SHORTANSWER', 1);
            define('LESSON_PAGE_ESSAY', 10);
            define('LESSON_PAGE_TRUEFALSE', 2);
            define('LESSON_PAGE_MULTICHOICE', 3);
            define('LESSON_PAGE_MATCHING', 5);
            define('LESSON_PAGE_NUMERICAL', 8);
        }
    }
}
