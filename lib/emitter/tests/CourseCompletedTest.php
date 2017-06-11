<?php
/**
 * Created by PhpStorm.
 * User: lee.kirkland
 * Date: 5/2/2016
 * Time: 4:52 PM
 */
namespace XREmitter\Tests;
use \XREmitter\Events\CourseCompleted as Event;

/**
 * Class CourseCompletedTest
 * @package XREmitter\Tests
 */
class CourseCompletedTest extends EventTest
{
    /**
     * @var string
     */
    protected static $recipeName = 'course_completed';

    /**
     * Sets up the tests.
     * @override EventTest
     */
    public function setup() {
        $this->event = new Event();
    }

    /**
     * Construct the input for the test.
     * @return array
     */
    protected function constructInput() {
        return array_merge(
            parent::constructInput(),
            $this->contructObject('course')
        );
    }

    /**
     * Asserts the output is the same as the input.
     * @param $input
     * @param $output
     */
    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertVerb('http://adlnet.gov/expapi/verbs/completed', 'completed', $output['verb']);
        $this->assertObject('course', $input, $output['object']);
    }
}
