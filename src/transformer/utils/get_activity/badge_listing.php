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
 * Transformer utility for retrieving (badge listing) activities.
 *
 * @package   logstore_xapi
 * @copyright Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

namespace src\transformer\utils\get_activity;

use src\transformer\utils as utils;

/**
 * Transformer utility for retrieving the badge listing.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $course
 * @param int $badgetype
 * @return array
 */
function badge_listing(array $config, \stdClass $course, int $badgetype): array{

    $courselang = utils\get_course_lang($course);
    $url = $config['app_url'].'badges/view.php?type='.$badgetype.'&id='.$course->id;

    return [
        'id' => $url,
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/collection-simple',
            'name' => [
                $courselang => 'List of badges',
            ],
        ],
    ];

}
