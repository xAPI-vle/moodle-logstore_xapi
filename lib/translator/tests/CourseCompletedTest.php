<?php
namespace MXTranslator\Tests;
use \MXTranslator\Events\CourseCompleted as Event;

/**
 * Class CourseCompletedTest
 * @package MXTranslator\Tests
 */
class CourseCompletedTest extends EventTest {
    /**
     * @var string
     */
    protected static $recipe_name = 'course_completed';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event();
    }

    /**
     * Constructs the Input with course completed
     * @return array
     */
    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'event' => $this->constructEvent('\core\event\course_completed'),
        ]);
    }

    /**
     * Constructs the event with the given event name
     * @param $event_name
     * @return array
     */
    private function constructEvent($event_name) {
        return [
            'eventname' => $event_name,
            'timecreated' => 1433946701,
        ];
    }

    /**
     * Assets that the input is the same as the output.
     * @param $input
     * @param $output
     */
    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertCourse($input['course'], $output, 'course');
    }
}