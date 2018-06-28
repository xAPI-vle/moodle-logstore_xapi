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

use \MXTranslator\Events\FacetofaceAttend as Event;

class facetoface_attend_test extends facetoface_enrol_test {
    protected static $recipename = 'training_session_attend';

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
            'signups' => [
                "1" => $this->construct_signup("1"),
                "2" => $this->construct_signup("2")
            ]
        ]);
    }

    private function construct_signup($facetofaceid) {
        $signups = (object) [
            "id" => $facetofaceid,
            "sessionid" => "1",
            "userid" => "1",
            "mailedreminder" => "0",
            "discountcode" => null,
            "notificationtype" => "3",
            "statuses" => [
                "1" => $this->construct_status("1"),
                "2" => $this->construct_status("2"),
                "3" => $this->construct_final_status("3"),
            ],
            "attendee" => $this->construct_user()
        ];

        return $signups;
    }

    private function construct_status($facetofaceid) {
        return (object) [
            "id" => $facetofaceid,
            "signupid" => "4",
            "statuscode" => "90",
            "superceded" => "1",
            "grade" => "50.00000",
            "note" => "",
            "advice" => null,
            "createdby" => "1",
            "timecreated" => "143394660" . $facetofaceid // Earlier than the final status.
        ];
    }

    private function construct_final_status($facetofaceid) {
        return (object) [
            "id" => $facetofaceid,
            "signupid" => "4",
            "statuscode" => "100",
            "superceded" => "0",
            "grade" => "100.00000",
            "note" => "",
            "advice" => null,
            "createdby" => "1",
            "timecreated" => "1433946701" // Must be the same as timecreated in EventTest.php.
        ];
    }

    protected function assert_outputs($input, $output) {
        // Output is an associative array.
        $this->assertEquals(0, count(array_filter(array_keys($output), 'is_string')));
        // Length of output is 3.
        $this->assertEquals(2 , count($output));
    }

    protected function assert_output($input, $output) {
        parent::assert_output($input, $output);
        $this->assertEquals($input['signups']['1']->attendee->id, $output['attendee_id']);
        $this->assertEquals($input['signups']['1']->attendee->url, $output['attendee_url']);
        $this->assertEquals($input['signups']['1']->attendee->fullname, $output['attendee_name']);

        $sessionduration = 0;
        foreach ($input['session']->dates as $index => $date) {
            $sessionduration -= $date->timestart;
            $sessionduration += $date->timefinish;
        }

        $this->assertEquals("PT" . $sessionduration . "S", $output['attempt_duration']);
        $this->assertEquals(true, $output['attempt_completion']);
    }
}
