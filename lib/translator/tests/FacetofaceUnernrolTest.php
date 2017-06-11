<?php namespace MXTranslator\Tests;
use \MXTranslator\Events\FacetofaceUnenrol as Event;

class FacetofaceUnenrolTest extends FacetofaceEnrolTest {
    protected static $recipe_name = 'training_session_unenrol';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event();
    }
}
