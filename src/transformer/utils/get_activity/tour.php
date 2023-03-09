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
 * Transformer utility for retrieving (tour) activities.
 *
 * @package   logstore_xapi
 * @copyright 2023 Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

namespace src\transformer\utils\get_activity;

/**
 * Transformer utility for retrieving tour activities.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $tour
 * @param string $lang The language of the book.
 * @return array
 */

function tour(array $config, \stdClass $tour, string $lang): array {

    $url = $config['app_url'] . '/admin/tool/usertours/configure.php?id=' . $tour->id . '&action=viewtour';
    $name = property_exists($tour, 'name') ? $tour->name : 'Tour';

    return [
        'id' => $url,
        'definition' => [
            'type' => 'http://adlnet.gov/expapi/activities/media',
            'name' => [
                $lang => $name . ' user tour'
            ],
        ],
    ];
}
