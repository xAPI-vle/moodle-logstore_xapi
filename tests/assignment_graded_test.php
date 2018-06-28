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

namespace Tests;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/xapi_testcase.php');

class assignment_graded_test extends xapi_testcase {
    protected function construct_input() {
        return array_merge(parent::construct_input(), [
            'objecttable' => 'assign_grades',
            'objectid' => '1',
            'eventname' => '\mod_assign\event\submission_graded',
        ]);
    }
}
