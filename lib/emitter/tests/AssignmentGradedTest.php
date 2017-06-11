<?php namespace XREmitter\Tests;
use \XREmitter\Events\AssignmentGraded as Event;

class AssignmentGradedTest extends EventTest {
    protected static $recipe_name = 'assignment_graded';

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
            $this->constructUser('graded_user'),
            [
                'grade_score_raw' => 47,
                'grade_score_min' => 0,
                'grade_score_max' => 100,
                'grade_score_scaled' => 0.47,
                'grade_success' => true,
                'grade_completed' => true,
                'grade_comment' => 'test comment from instructor'
            ]
        );
    }

    protected function assertOutput($input, $output) {
        $this->assertUser($input, $output['actor'], 'graded_user');
        $this->assertObject('app', $input, $output['context']['contextActivities']['grouping'][0]);
        $this->assertObject('source', $input, $output['context']['contextActivities']['category'][0]);
        $this->assertLog($input, $output);
        $this->assertInfo(
            $input['context_info'],
            $output['context']['extensions']['http://lrs.learninglocker.net/define/extensions/info']
        );
        $this->assertValidXapiStatement($output);
        $this->assertVerb('http://adlnet.gov/expapi/verbs/scored', 'recieved grade for', $output['verb']);
        $this->assertObject('module', $input, $output['object']);
        $this->assertObject('course', $input, $output['context']['contextActivities']['parent'][0]);
        $this->assertEquals($input['grade_score_raw'], $output['result']['score']['raw']);
        $this->assertEquals($input['grade_score_min'], $output['result']['score']['min']);
        $this->assertEquals($input['grade_score_max'], $output['result']['score']['max']);
        $this->assertEquals($input['grade_score_scaled'], $output['result']['score']['scaled']);
        $this->assertEquals($input['grade_success'], $output['result']['success']);
        $this->assertEquals($input['grade_completed'], $output['result']['completion']);
        $this->assertEquals($input['grade_comment'], $output['result']['response']);
        $this->assertUser($input, $output['context']['instructor'], 'user');
    }
}
