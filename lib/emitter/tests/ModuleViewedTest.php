<?php namespace XREmitter\Tests;
use \XREmitter\Events\ModuleViewed as Event;

class ModuleViewedTest extends EventTest {
    protected static $recipeName = 'module_viewed';

    /**
     * Sets up the tests.
     * @override EventTest
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function constructInput() {
        return array_merge(
            parent::constructInput(),
            $this->contructObject('course'),
            $this->contructObject('module')
        );
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertVerb('http://id.tincanapi.com/verb/viewed', 'viewed', $output['verb']);
        $this->assertObject('module', $input, $output['object']);
        $this->assertObject('course', $input, $output['context']['contextActivities']['grouping'][1]);
    }
}
