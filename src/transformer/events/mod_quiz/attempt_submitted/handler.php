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

namespace src\transformer\events\mod_quiz\attempt_submitted;

defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;
use src\transformer\events\mod_quiz\question_answered as question_answered;

function handler(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $sql = "SELECT qat.* FROM {quiz_attempts} as qa
join {question_usages} as qu on qu.id = qa.uniqueid
join {question_attempts} as qat on qat.questionusageid = qu.id
where qa.id = $event->objectid";
    // Need to do complex lookup due to the way Moodle links Quiz Attempt with Question Attempt.
    $questionattempts = $repo->read_records_sql($sql);

    return array_merge(
        attempt_submitted($config, $event),
        array_reduce($questionattempts, function ($result, $questionattempt) use ($config, $event) {
            return array_merge($result, question_answered\handler($config, $event, $questionattempt));
        }, [])
    );
}