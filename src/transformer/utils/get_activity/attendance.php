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
 * Transformer utility for retrieving (attendance) activities.
 *
 * @package   logstore_xapi
 * @copyright 2023 Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity;

use src\transformer\utils as utils;

/**
 * Transformer utility for retrieving (attendance) activities.
 *
 * @param array $config The transformer config settings.
 * @param string $cmid The id of the course module.
 * @param string $sessionid The id of the session.
 * @param string $grouptype The id of the group type.
 * @param string $lang The language of the attendance.
 * @return array
 */
function attendance(array $config, string $cmid, string $sessionid, string $grouptype, string $lang) {

    return [
        'id' => $config['app_url'] . '/take.php?id=' . $cmid . '&sessionid=' . $sessionid . '&grouptype=' . $grouptype,
        'definition' => [
            'type' => 'http://vocab.xapi.fr/activities/registration',
            'name' => [
                $lang => 'Attendance',
            ],
        ],
    ];
}
