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

namespace MXTranslator\Tests;

defined('MOODLE_INTERNAL') || die();

use \MXTranslator\Events\FacetofaceEnrol as Event;

class facetoface_enrol_test extends module_viewed_test {
    protected static $recipename = 'training_session_enrol';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        parent::setup();
        $this->event = new Event();
    }

    protected function construct_input() {
        return array_merge(parent::construct_input(), [
            'session' => $this->construct_session()
        ]);
    }

    private function construct_session() {
        return (object) [
            "id" => "1",
            "facetoface" => "1",
            "capacity" => "10",
            "allowoverbook" => "0",
            "details" => "",
            "datetimeknown" => "0",
            "duration" => "0",
            "normalcost" => "0",
            "discountcost" => "0",
            "timecreated" => "1464179438",
            "timemodified" => "0",
            "type" => "facetoface_sessions",
            "dates" => [
                "1" => (object) [
                    "id" => "1",
                    "sessionid" => "1",
                    "timestart" => "1464176400",
                    "timefinish" => "1464179400"
                ]
            ],
            'url' => 'http://www.example.com/session_url'
        ];
    }

    protected function assert_output($input, $output) {
        parent::assert_output($input, $output);
        $this->assertEquals($input['session']->url, $output['session_url']);
        $sessionname = 'Session '.$input['session']->id.' of '.$input['module']->name;
        $this->assertEquals($sessionname, $output['session_name']);
        $this->assertEquals($sessionname, $output['session_description']);
        $this->assertEquals('http://activitystrea.ms/schema/1.0/event', $output['session_type']);
    }
}
