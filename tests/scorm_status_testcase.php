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

class scorm_status_testcase extends xapi_testcase {
    protected function construct_input() {
        return array_merge(parent::construct_input(), [
          'objecttable' => 'scorm_scoes_track',
          'objectid' => 1,
          'contextinstanceid' => 1,
          'eventname' => '\mod_scorm\event\status_submitted',
          'other' => 'a:3:{s:9:"attemptid";i:2;s:10:"cmielement";s:22:"cmi.core.lesson_status";s:8:"cmivalue";s:6:"failed";}';
        ]);
    }
}
