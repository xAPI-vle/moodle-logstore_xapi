<?php namespace Tests;

class DiscussionViewedTest extends TestCase {
    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => 'forum_discussions',
            'objectid' => 1,
            'eventname' => '\mod_forum\event\discussion_viewed',
        ]);
    }
}
