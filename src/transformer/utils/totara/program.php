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

namespace src\transformer\utils\totara;
defined('MOODLE_INTERNAL') || die();

function program(array $config, \stdClass $program, $lang) {
    $programname = $program->fullname ? $program->fullname : 'A Totara program';

    $object = [
        'id' => $config['app_url'].'/totara/program/view.php?id='.$program->id,
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/lms/program',
            'name' => [
                $lang => $programname,
            ],
        ],
    ];

    return $object;
}
