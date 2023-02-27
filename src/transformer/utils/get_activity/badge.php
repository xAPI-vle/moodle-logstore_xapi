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
 * Transformer utility for retrieving (badge) activities.
 *
 * @package   logstore_xapi
 * @copyright Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity;

/**
 * Transformer utility for retrieving (badge) activities.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $badge The badge object.
 * @param string $badgehash The discussion object.
 * @param string $lang
 * @return array
 */
function badge(array $config, \stdClass $badge, string $badgehash, string $lang): array {

    $badgeurl = $config['app_url'] . '/badges/badge.php?hash=' . $badgehash;
    $badgename = property_exists($badge, 'name') ? $badge->name : 'Badge';

    return [
        'id' => $badgeurl,
        'definition' => [
            'type' => 'http://activitystrea.ms/schema/1.0/badge',
            'name' => [
                $lang => $badgename,
            ],
        ],
    ];
}
