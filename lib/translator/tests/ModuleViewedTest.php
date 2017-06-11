<?php namespace MXTranslator\Tests;
use \MXTranslator\Events\ModuleViewed as Event;

class ModuleViewedTest extends CourseViewedTest {
    protected static $recipeName = 'module_viewed';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'module' => $this->constructModule(),
        ]);
    }

    private function constructModule() {
        return (object) [
            'url' => 'http://www.example.com/module_url',
            'name' => 'Test module_name',
            'intro' => 'Test module_intro',
            'type' => 'moodle_module',
        ];
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertModule($input['module'], $output, 'module');
    }

    private function assertModule($input, $output, $type) {
        $ext_key = 'http://lrs.learninglocker.net/define/extensions/moodle_module';
        $this->assertEquals($input->url, $output[$type.'_url']);
        $this->assertEquals($input->name, $output[$type.'_name']);
        $this->assertEquals($input->intro, $output[$type.'_description']);
        $this->assertEquals(static::$xapiType.$input->type, $output[$type.'_type']);
        $this->assertEquals($input, $output[$type.'_ext']);
        $this->assertEquals($ext_key, $output[$type.'_ext_key']);
    }
}
