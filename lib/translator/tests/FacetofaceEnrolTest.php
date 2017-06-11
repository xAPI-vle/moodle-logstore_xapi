<?php namespace MXTranslator\Tests;
use \MXTranslator\Events\FacetofaceEnrol as Event;

class FacetofaceEnrolTest extends ModuleViewedTest {
    protected static $recipe_name = 'training_session_enrol';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'session' => $this->constructSession()
        ]);
    }

    private function constructSession() {
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

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertEquals($input['session']->url, $output['session_url']);
        $sessionName = 'Session '.$input['session']->id.' of '.$input['module']->name;
        $this->assertEquals($sessionName, $output['session_name']);
        $this->assertEquals($sessionName, $output['session_description']);
        $this->assertEquals('http://activitystrea.ms/schema/1.0/event', $output['session_type']);
    }
}
