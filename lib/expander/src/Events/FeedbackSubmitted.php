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

class FeedbackSubmitted extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        $attempt = $this->repo->read_feedback_attempt($opts['objectid']);
        $attempt->timemodified = $this->time_selector($attempt);
        return array_merge(parent::read($opts), [
            'module' => $this->repo->read_module($attempt->feedback, 'feedback'),
            'questions' => $this->repo->read_feedback_questions($attempt->feedback),
            'attempt' => $attempt,
        ]);
    }

    /**
     * Checks to see which time element in $attempt is valid and if none are available
     * returns time()
     * @param $attempt
     * @return int
     */
    private function time_selector($attempt) {
        $retvalue = time();
        if (!empty($attempt->timemodified)) {
            $retvalue = $attempt->timemodified;
        } else if (!empty($attempt->timefinished)) {
            $retvalue = $attempt->timefinished;
        } else if (!empty($attempt->timestarted)) {
            $retvalue = $attempt->timestarted;
        }

        return $retvalue;
    }
}
