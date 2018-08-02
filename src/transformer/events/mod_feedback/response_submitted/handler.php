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

namespace src\transformer\events\mod_feedback\response_submitted;

defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;
use src\transformer\events\mod_feedback\item_answered as item_answered;

function handler(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $feedbackvalues = $repo->read_records('feedback_value', [
        'completed' => $event->objectid
    ]);

    return array_merge(
        response_submitted($config, $event),
        array_reduce($feedbackvalues, function ($result, $feedbackvalue) use ($config, $event) {
            return array_merge($result, item_answered\handler($config, $event, $feedbackvalue));
        }, [])
    );
}