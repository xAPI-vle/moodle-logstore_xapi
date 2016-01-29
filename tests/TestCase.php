<?php namespace Tests;
use \PHPUnit_Framework_TestCase as PhpUnitTestCase;
use \XREmitter\Controller as xapi_controller;
use \XREmitter\Tests\TestRepository as xapi_repository;
use \XREmitter\Tests\TestRemoteLrs as xapi_remote_lrs;
use \MXTranslator\Controller as translator_controller;
use \LogExpander\Controller as moodle_controller;
use \LogExpander\Tests\TestRepository as moodle_repository;
use \Locker\XApi\Statement as LockerStatement;
use \TinCan\Statement as TinCanStatement;

abstract class TestCase extends PhpUnitTestCase {
    protected $xapi_controller, $moodle_controller, $translator_controller, $cfg;

    /**
     * Sets up the tests.
     * @override PhpUnitTestCase
     */
    public function setup() {
        $this->cfg = (object) [
            'wwwroot' => 'http://www.example.com',
            'release' => '1.0.0',
        ];
        $this->xapi_controller = new xapi_controller(new xapi_repository(new xapi_remote_lrs('', '1.0.1', '', '')));
        $this->moodle_controller = new moodle_controller(new moodle_repository((object) [], $this->cfg));
        $this->translator_controller = new translator_controller();
    }

    public function testCreateEvent() {
        $input = $this->constructInput();

        $moodle_events = $this->moodle_controller->createEvents([$input]);
        $this->assertNotNull($moodle_events, 'Check that the events exist in the expander controller.');

        $translator_events = $this->translator_controller->createEvents($moodle_events);
        $this->assertNotNull($translator_events, 'Check that the events exist in the translator controller.');

        $xapi_events = $this->xapi_controller->createEvents($translator_events);
        $this->assertNotNull($xapi_events, 'Check that the events exist in the emitter controller.');

        $this->assertOutput($input, $xapi_events);
    }

    protected function assertOutput($input, $output) {
        foreach ($output as $outputpart) {
            $this->assertValidXapiStatement((new TinCanStatement($outputpart))->asVersion('1.0.0'));
        }
    }

    protected function assertValidXapiStatement($output) {
        $errors = LockerStatement::createFromJson(json_encode($output))->validate();
        $errors_json = json_encode(array_map(function ($error) {
            return (string) $error;
        }, $errors));
        $this->assertEmpty($errors, $errors_json);
    }

    protected function constructInput() {
        return [
            'userid' => '1',
            'relateduserid' => '1',
            'courseid' => '1',
            'timecreated' => 1433946701,
            'eventname' => '\core\event\course_viewed',
        ];
    }
}
