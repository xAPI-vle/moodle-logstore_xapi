<?php namespace XREmitter\Tests;
use \XREmitter\Events\ScormLaunched as Event;

class ScormLaunchedTest extends EventTest {
    protected static $recipe_name = 'scorm_launched';

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
        $this->assertVerb('http://adlnet.gov/expapi/verbs/launched', 'launched', $output['verb']);
        $this->assertObject('module', $input, $output['object']);
        $this->assertObject('course', $input, $output['context']['contextActivities']['grouping'][1]);
    }
}
