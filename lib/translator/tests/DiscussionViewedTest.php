<?php namespace MXTranslator\Tests;
use \MXTranslator\Events\DiscussionViewed as Event;

class DiscussionViewedTest extends ModuleViewedTest {
    protected static $recipeName = 'discussion_viewed';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'discussion' => $this->constructDiscussion(),
        ]);
    }
    
    private function constructDiscussion() {
        return (object) [
            'url' => 'http://www.example.com/discussion_url',
            'name' => 'Test discussion_name',
            'type' => 'moodle_discussion',
            'ext' => 'discussion_ext',
            'ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_discussion',
        ];
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertDiscussion($input['discussion'], $output, 'discussion');
    }

    private function assertDiscussion($input, $output, $type) {
        $ext_key = 'http://lrs.learninglocker.net/define/extensions/moodle_discussion';
        $this->assertEquals($input->url, $output[$type.'_url']);
        $this->assertEquals($input->name, $output[$type.'_name']);
        $this->assertEquals('A Moodle discussion.', $output[$type.'_description']);
        $this->assertEquals(static::$xapiType.$input->type, $output[$type.'_type']);
        $this->assertEquals($input, $output[$type.'_ext']);
        $this->assertEquals($ext_key, $output[$type.'_ext_key']);
    }
}
