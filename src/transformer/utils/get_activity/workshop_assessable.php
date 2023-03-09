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
 * Transformer utility for retrieving (assessable) activities.
 *
 * @package   logstore_xapi
 * @copyright 2023 Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity;

use Exception;

/**
 * Transformer utility for retrieving (assessable) activities.
 *
 * @param array $config The transformer config settings.
 * @param string $lang The language of the attendance.
 * @param int $assessableid
 * @param int $cmid The id of the course module.
 * @return array
 */

function workshop_assessable(array $config, string $lang, int $assessableid, int $cmid) {

    try {
        $repo = $config['repo'];
        $assessable = $repo->read_record_by_id('workshop_submissions', $assessableid);
        $name = property_exists($assessable, 'title') ? $assessable->title : 'Submission';

    } catch (Exception $e) {
        // OBJECT_NOT_FOUND.
        $name = 'assessable id: ' . $assessableid;
    }


    $url = $config['app_url'] . '/mod/workshop/submission.php?cmid=' . $cmid . '&id=' . $assessableid;


    return [
        'id' => $url,
        'definition' => [
            'type' => 'http://adlnet.gov/expapi/activities/assessment',
            'name' => [
                $lang => $name,
            ],
        ],
    ];
}
