<?php namespace LogExpander\Tests;
use \LogExpander\Events\DiscussionEvent as Event;

class DiscussionEventTest extends EventTest {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event($this->repo);
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => 'forum_discussions',
            'objectid' => 1,
            'eventname' => '\mod_forum\event\discussion_viewed',
        ]);
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertModule(1, $output['module'], 'forum');
        $this->assertDiscussion($input['objectid'], $output['discussion']);
    }
}
