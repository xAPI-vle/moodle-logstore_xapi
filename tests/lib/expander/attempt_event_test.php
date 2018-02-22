<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace LogExpander\Tests;

defined('MOODLE_INTERNAL') || die();

use \LogExpander\Events\AttemptEvent as Event;

class attempt_event_test extends event_test {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        parent::setup();
        $this->event = new Event($this->repo);
    }

    protected function construct_input() {
        return array_merge(parent::construct_input(), [
            'objecttable' => 'quiz_attempts',
            'objectid' => 1,
            'eventname' => '\mod_quiz\event\attempt_preview_started',
        ]);
    }

    protected function assert_output($input, $output) {
        parent::assert_output($input, $output);
        $this->assert_module(1, $output['module'], 'quiz');
        $this->assert_attempt($input['objectid'], $output['attempt']);
        $this->assertEquals(5, $output['grade_items']->gradepass);
        $this->assertEquals(5, $output['grade_items']->grademax);
        $this->assertEquals(0, $output['grade_items']->grademin);
        $this->assert_question_attempts($output['attempt']->questions);
        $this->assert_questions($output['questions']);
    }

    protected function assert_question_attempts($output) {
        $this->assertEquals('1', $output['1']->id);
        $this->assertEquals('2', $output['1']->steps['2']->id);
        $this->assertEquals('2', $output['1']->steps['1']->data['2']->id);
    }

    protected function assert_questions($output) {
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
