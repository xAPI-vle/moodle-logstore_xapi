<?php namespace MXTranslator\Tests;
use \MXTranslator\Events\AttemptStarted as Event;

class AttemptStartedTest extends ModuleViewedTest {
    protected static $recipeName = 'attempt_started';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'attempt' => $this->constructAttempt(),
        ]);
    }

    private function constructAttempt() {
        return (object) [
            'url' => 'http://www.example.com/attempt_url',
            'name' => 'Test attempt_name',
            'type' => 'moodle_attempt',
            'timestart' => 1433946701,
            'timefinish' => 1433946701,
            'sumgrades' => 1,
            'state' => 'finished',
        ];
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertAttempt($input['attempt'], $output);
    }

    protected function assertAttempt($input, $output) {
        $ext_key = 'http://lrs.learninglocker.net/define/extensions/moodle_attempt';
        $this->assertEquals($input->url, $output['attempt_url']);
        $this->assertEquals($input->name, $output['attempt_name']);
        $this->assertEquals(static::$xapiType.$input->type, $output['attempt_type']);
        $this->assertEquals($input, $output['attempt_ext']);
        $this->assertEquals($ext_key, $output['attempt_ext_key']);
    }
}
