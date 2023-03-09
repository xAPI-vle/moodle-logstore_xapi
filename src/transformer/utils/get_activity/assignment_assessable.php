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
 * Transformer utility for retrieving (forum assessable) activities.
 *
 * @package   logstore_xapi
 * @copyright 2023 Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity;

/**
 * Transformer utility for retrieving (forum assessable) activities.
 *
 * @param array $config The transformer config settings.
 * @param string $lang The language of the attendance.
 * @param int $cmid
 * @param string $component
 * @return array
 */

function assignment_assessable(array $config, string $lang, int $cmid, string $component): array {

    if ($component ==  'assignsubmission_file') {
        $type = 'http://activitystrea.ms/schema/1.0/file';
        $name = 'file';
    } else {
        $type = 'http://activitystrea.ms/schema/1.0/article';
        $name = 'online text';
    }

    $url = $config['app_url'] . '/mod/assign/view.php?id=' . $cmid;

    return [
        'id' => $url,
        'definition' => [
            'type' => $type,
            'name' => [
                $lang => $name,
            ],
        ],
    ];
}
