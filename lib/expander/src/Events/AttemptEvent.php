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

namespace LogExpander\Events;

defined('MOODLE_INTERNAL') || die();

class AttemptEvent extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        $attempt = $this->repo->read_attempt($opts['objectid']);
        $gradeitems = $this->repo->read_grade_items($attempt->quiz, 'quiz');
        $attempt->questions = $this->repo->read_question_attempts($attempt->id);
        $questions = $this->repo->read_questions($attempt->quiz);

        return array_merge(parent::read($opts), [
            'attempt' => $attempt,
            'module' => $this->repo->read_module($attempt->quiz, 'quiz'),
            'grade_items' => $gradeitems,
            'questions' => $questions
        ]);
    }
}
