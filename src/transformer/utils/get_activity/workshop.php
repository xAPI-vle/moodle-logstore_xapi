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
 * Transformer utility for retrieving (workshop) activities.
 *
 * @package   logstore_xapi
 * @copyright Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

namespace src\transformer\utils\get_activity;


/**
 * Transformer utility for retrieving the workshop.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $workshop The workshop object.
 * @param int $workshopid The id of the workshop.
 * @param string $lang The language of the workshop.
 * @return array
 */
function workshop(array $config, \stdClass $workshop, int $workshopid, string $lang): array {

    $workshopurl = $config['app_url'].'/mod/book/tool/print/index.php?id=' . $workshopid; //vedi url
    $workshopname = property_exists($workshop, 'name') ? $workshop->name : 'Workshop';

    return [
        'id' => $workshopurl,
        'definition' => [
            'type' => 'http://vocab.xapi.fr/activities/workshop',
            'name' => [
                $lang => $workshopname,
            ],
        ],
    ];
}
