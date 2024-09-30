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

/**
 * Transform for calendar subscription updated event.
 *
 * @package   logstore_xapi
 * @copyright Daniel Bell <daniel@yetanalytics.com>
 *            
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils;

/**
 * fn that generates parent for xapi events and includes it in statement
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $statement The statement to get a parent added.
 * @param \stdClass $event The course that is parent
 * @return array
 */

function add_parent(array $config, array $statement, \stdClass $course){
    $lang = get_course_lang($course);
    $statement['context']['contextActivities']['parent'] = [[
        'id' => $config['app_url'].'/course/view.php?id='.$course->id,
        'objectType' => 'Activity',
        'definition' => [
            'name' => [$lang => $course->fullname],
            'description' => [$lang => $course->summary],
            'type' => 'https://w3id.org/xapi/cmi5/activitytype/course'
        ]
    ]];

    return $statement;
}



    
