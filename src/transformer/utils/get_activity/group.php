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
 * Transformer utility for retrieving (group) activities.
 *
 * @package   logstore_xapi
 * @copyright 2023 Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity;

/**
 * Transformer utility for retrieving (group) activities.
 *
 * @param array $config The transformer config settings.
 * @param string $lang The language of the group.
 * @param \stdClass $group The object group.
 * @return array
 */

function group(array $config, string $lang, \stdClass $group): array {

    $groupurl = $config['app_url'] . '/group/members.php?group=' . $group->id ;
    $groupname = property_exists($group, 'name') ? $group->name : 'Group';

    return [
        'id' => $groupurl,
        'definition' => [
            'type' => 'http://activitystrea.ms/schema/1.0/group',
            'name' => [
                $lang => $groupname,
            ],
        ],
    ];
}
