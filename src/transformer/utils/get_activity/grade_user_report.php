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
 * Transformer utility for retrieving grade user report activities.
 *
 * @package   logstore_xapi
 * @copyright 2023 Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity;

use src\transformer\utils as utils;

/**
 * Transformer utility for retrieving grade user report activities.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $user The user object.
 * @param \stdClass $course The course object.
 * @param string $lang The language of the course.
 * @return array
 */
function grade_user_report(array $config, \stdClass $user, \stdClass $course, string $component, string $lang) {

    $fullname = utils\get_full_name($user);
    $userid = $user->id;

    if (array_key_exists('send_pseudo', $config) && $config['send_pseudo']) {
        $fullname = sha1($fullname);
        $userid = sha1($userid);
    }

    if ($component == 'gradereport_overview') {
        $url = $config['app_url'] . '/grade/report/overview/index.php';
        $name = 'grade overview report of '. $fullname;
    } else {
        $url = $config['app_url'] . '/grade/report/user/index.php?id=' . $course->id;
        $name = 'grade user report of '. $fullname;
    }

    return [
        'id' => $url,
        'definition' => [
            'type' => 'http://activitystrea.ms/schema/1.0/review',
            'name' => [
                $lang => $name,
            ],
            'extensions' => [
                'https://moodle.org/xapi/extensions/user_id' => $userid,
            ],
        ],
    ];
}
