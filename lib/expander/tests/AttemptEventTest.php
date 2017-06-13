<?php namespace LogExpander\Tests;
use \LogExpander\Events\AttemptEvent as Event;

class AttemptEventTest extends EventTest {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        $this->event = new Event($this->repo);
    }

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => 'quiz_attempts',
            'objectid' => 1,
            'eventname' => '\mod_quiz\event\attempt_preview_started',
        ]);
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertModule(1, $output['module'], 'quiz');
        $this->assertAttempt($input['objectid'], $output['attempt']);
        $this->assertEquals(5, $output['grade_items']->gradepass);
        $this->assertEquals(5, $output['grade_items']->grademax);
        $this->assertEquals(0, $output['grade_items']->grademin);
        $this->assertQuestionAttempts($output['attempt']->questions);
        $this->assertQuestions($output['questions']);

    }

    protected function assertQuestionAttempts($output) {
        $this->assertEquals('1', $output['1']->id);
        $this->assertEquals('2', $output['1']->steps['2']->id);
        $this->assertEquals('2', $output['1']->steps['1']->data['2']->id);
    }

    protected function assertQuestions($output) {
        $this->assertEquals('multichoice', $output['1']->qtype);
        $this->assertEquals('1', $output['1']->id);
        $this->assertEquals('1', $output['1']->answers['1']->id);
        $this->assertEquals($this->cfg->wwwroot . '/mod/question/question.php?id=1', $output['1']->url);

        $this->assertEquals('numerical', $output['2']->qtype);
        $this->assertEquals('1', $output['2']->numerical->answers['1']->id);
        $this->assertEquals('1', $output['2']->numerical->options->id);
        $this->assertEquals('1', $output['2']->numerical->units['1']->id);

        $this->assertEquals('match', $output['3']->qtype);
        $this->assertEquals('1', $output['3']->match->options->id);
        $this->assertEquals('1', $output['3']->match->subquestions['1']->id);

        $this->assertEquals('calculated', $output['4']->qtype);
        $this->assertEquals('1', $output['4']->calculated->answers['1']->id);
        $this->assertEquals('1', $output['4']->calculated->options->id);

        $this->assertEquals('shortanswer', $output['5']->qtype);
        $this->assertEquals('1', $output['5']->shortanswer->options->id);
    }
}
