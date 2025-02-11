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
 * Transformer utility for retrieving web calendar activities.
 *
 * @package   logstore_xapi
 * @copyright Milt Reder <milt@yetanalytics.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity;

use src\transformer\utils as utils;

/**
 * Transformer utility for retrieving web calendar activities.
 *
 * @param string $url The web calendar URL.
 * @return array
 */
function web_calendar(string $url) {
    return [
        ...base(),
        'id' => $url,
        'definition' => [
            'type' => 'https://xapi.edlm/profiles/edlm-lms/concepts/activity-types/calendar',
            'name' => [
                'en' => 'Web Calendar'
            ],
        ]
    ];
}
