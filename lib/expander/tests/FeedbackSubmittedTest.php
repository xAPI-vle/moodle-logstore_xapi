<?php namespace LogExpander\Tests;
use \LogExpander\Events\FeedbackSubmitted as Event;

class FeedbackSubittedTest extends EventTest {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event($this->repo);
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => 'feedback_completed',
            'objectid' => '1',
            'eventname' => '\mod_feedback\event\response_submitted',
        ]);
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertModule(1, $output['module'], 'feedback');
        $this->assertEquals('test_name', $output['questions'][1]->name);
        $this->assertEquals('http://www.example.com/mod/feedback/edit_item.php?id=1', $output['questions']['1']->url);
        $this->assertEquals('test_name', $output['questions'][1]->template->name);
        $this->assertEquals('http://www.example.com/mod/feedback/complete.php?id=1', $output['attempt']->url);
        $this->assertEquals('1', $output['attempt']->responses['1']->id);
    }
}
