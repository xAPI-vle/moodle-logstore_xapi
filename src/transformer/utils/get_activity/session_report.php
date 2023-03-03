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
 * Transformer utility for retrieving (session report) activities.
 *
 * @package   logstore_xapi
 * @copyright 2023 Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity;

/**
 * Transformer utility for retrieving (session report) activities.
 *
 * @param array $config The transformer config settings.
 * @param string $cmid The id of the course module.
 * @param array $other The field other of the event.
 * @param string $lang The language of the attendance.
 * @return array
 */
function session_report(array $config, string $cmid, array $other, string $lang): array {

    $studentid = empty($other['studentid']) ? '' : $other['studentid'];
    $mode = empty($other['mode']) ? '' : $other['mode'];
    $view = empty($other['view']) ? '' : $other['view'];
    $groupby = empty($other['groupby']) ? '' : $other['groupby'];
    $sesscourses = empty($other['sesscourses']) ? '' : $other['sesscourses'];
    $curdate = empty($other['curdate']) ? '' : $other['curdate'];

    $url = $config['app_url'] . '/mod/attendance/view.php?id=' . $cmid;

    if ($other['studentid'] != '') {
        $url = $url . '&studentid=' . $studentid;
    }
    if ($other['mode'] != '') {
        $url = $url . '&mode=' . $mode;
    }
    if ($other['view'] != '') {
        $url = $url . '&view=' . $view;
    }
    if ($other['groupby'] != '') {
        $url = $url . '&groupby=' . $groupby;
    }
    if ($other['sesscourses'] != '') {
        $url = $url . '&sesscourses=' . $sesscourses;
    }
    if ($other['curdate'] != '') {
        $url = $url . '&curdate=' . $curdate;
    }

    return [
        'id' => $url,
        'definition' => [
            'type' => 'http://activitystrea.ms/schema/1.0/page',
            'name' => [
                $lang => 'Session report',
            ],
        ],
    ];
}
