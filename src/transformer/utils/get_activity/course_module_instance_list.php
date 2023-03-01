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
 * Transformer utility for retrieving (module instance list) activities.
 *
 * @package   logstore_xapi
 * @copyright Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

namespace src\transformer\utils\get_activity;

/**
 * Transformer utility for retrieving the module instance list.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $course The course object.
 * @param string $coursemodule The module of the course.
 * @param string $lang The language of the module instance.
 * @return array
 */
function course_module_instance_list(array $config, \stdClass $course, string $coursemodule, string $lang): array {

    $coursemodule = explode('_', $coursemodule)[1];
    $url = $config['app_url'].'/mod/'.$coursemodule.'/index.php?id='.$course->id;

    return [
        'id' => $url,
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/collection-simple',
            'name' => [
                $lang => 'List of module instances',
            ],
        ],
    ];
}
