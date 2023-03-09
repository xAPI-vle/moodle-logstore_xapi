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
 * Transformer utility for retrieving (h5p statement) activities.
 *
 * @package   logstore_xapi
 * @copyright 2023 Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity;

/**
 * Transformer utility for retrieving (h5p statement) activities.
 *
 * @param array $config The transformer config settings.
 * @param string $lang The language of the badge.
 * @param \stdClass $activity The h5p activity object.
 * @param int $cmid The module id.
 * @param \stdClass $user The user object.
 * @return array
 */
function h5p_statement(array $config, string $lang, \stdClass $activity, \stdClass $user, int $cmid): array {

    $url = $config['app_url'] . '/mod/h5pactivity/grade.php?id=' . $cmid . '&user=' . $user->id;
    $name = property_exists($activity, 'name') ? $activity->name : 'H5P Activity';

        return [
        'id' => $url,
        'definition' => [
            'type' => 'http://adlnet.gov/expapi/activities/cmi.interaction',
            'interactionType' => 'fill-in',
            'name' => [
                $lang => $name,
            ],
        ],
    ];
}
