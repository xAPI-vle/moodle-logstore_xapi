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

namespace src\transformer\utils\extensions;
defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

function jisc(array $config, \stdClass $event, $course) {
    if (utils\is_enabled_config($config, 'send_jisc_data')) {
        return array_merge(
            [
                'http://xapi.jisc.ac.uk/sessionId' => $config['session_id'],
                'http://id.tincanapi.com/extension/ip-address' => $event->ip,
                'http://xapi.jisc.ac.uk/statementCat' => 'VLE',
            ],
            course_area($course)
        );
    }
    return [];
}
