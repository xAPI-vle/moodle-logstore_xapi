<?php namespace MXTranslator\Tests;
use \MXTranslator\Events\ScormLaunched as Event;

class ScormLaunchedTest extends ModuleViewedTest {
    protected static $recipe_name = 'scorm_launched';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event();
    }
}
