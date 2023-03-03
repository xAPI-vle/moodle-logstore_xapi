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
 * Transformer utility for retrieving (lesson) activities.
 *
 * @package   logstore_xapi
 * @copyright 2023 Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

namespace src\transformer\utils\get_activity;

/**
 * Transformer utility for retrieving the lesson.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $lesson The lesson object.
 * @param int $lessonid The id of the lesson.
 * @param string $lang The language of the lesson.
 * @return array
 */

function lesson(array $config, \stdClass $lesson, int $lessonid, string $lang): array {

    $lessonurl = $config['app_url'].'/mod/book/tool/print/index.php?id=' . $lessonid; //vedi url
    $lessonname = property_exists($lesson, 'name') ? $lesson->name : 'Workshop';

    return [
        'id' => $lessonurl,
        'definition' => [
            'type' => 'http://adlnet.gov/expapi/activities/lesson',
            'name' => [
                $lang => $lessonname,
            ],
        ],
    ];
}
