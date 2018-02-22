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

namespace XREmitter\Events;

defined('MOODLE_INTERNAL') || die();

class ScormScoreRawSubmitted extends ScormEvent {
    protected static $verbdisplay;

    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return array_merge_recursive(parent::read($opts), [
            'verb' => $this->read_scorm_verb($opts),
            'result' => [
                'score' => [
                    'raw' => $opts['scorm_score_raw'],
                    'min' => $opts['scorm_score_min'],
                    'max' => $opts['scorm_score_max'],
                    'scaled' => $opts['scorm_score_scaled']
                ],
            ],
        ]);
    }
}
