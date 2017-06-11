<?php namespace XREmitter\Tests;
use \XREmitter\Events\DiscussionViewed as Event;

class DiscussionViewedTest extends EventTest {
    protected static $recipe_name = 'discussion_viewed';

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
            $this->contructObject('module'),
            $this->constructDiscussion()
        );
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertVerb('http://id.tincanapi.com/verb/viewed', 'viewed', $output['verb']);
        $this->assertObject('discussion', $input, $output['object']);
        $this->assertObject('course', $input, $output['context']['contextActivities']['grouping'][1]);
        $this->assertObject('module', $input, $output['context']['contextActivities']['grouping'][2]);
    }
}
