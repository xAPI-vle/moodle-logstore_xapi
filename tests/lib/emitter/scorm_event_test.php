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

namespace XREmitter\Tests;

defined('MOODLE_INTERNAL') || die();

use \XREmitter\Events\ScormEvent as Event;

class scorm_event_test extends event_test {
    protected static $recipename = 'scorm_event';

    /**
     * Sets up the tests.
     * @override ModuleViewedTest
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function construct_input() {
        return array_merge(
            parent::construct_input(),
            $this->construct_object('course'),
            $this->construct_object('module'),
            $this->construct_scorm_tracking(),
            $this->construct_scorm_scoes()
        );
    }

    protected function construct_scorm_tracking() {
        return [
            'scorm_score_raw' => 100,
            'scorm_score_min' => 0,
            'scorm_score_scaled' => 1,
            'scorm_score_max' => 100,
            'scorm_status' => 'completed',
        ];
    }

    protected function construct_scorm_scoes() {
        return [
            'scorm_scoes_id' => 1,
            'scorm_scoes_url' => 'http://www.example.com/module_url',
            'scorm_scoes_type' => static::$xapitype . 'sco',
            'scorm_scoes_name' => 'Sco name',
            'scorm_scoes_description' => 'Sco Description',
        ];
    }

    protected function assert_output($input, $output) {
        $this->assert_user($input, $output['actor'], 'user');
        $this->assert_object('course', $input, $output['context']['contextActivities']['grouping'][0]);
        $this->assert_object('module', $input, $output['object']);
        $this->assert_object('scorm_scoes', $input, $output['context']['contextActivities']['grouping'][1]);
    }

}
