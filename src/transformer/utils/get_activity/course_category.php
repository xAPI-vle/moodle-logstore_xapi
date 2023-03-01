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
 * Transformer utility for retrieving category data.
 *
 * @package   logstore_xapi
 * @copyright Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

namespace src\transformer\utils\get_activity;

/**
 * Transformer utility for retrieving category data.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $core_course_category The course category object.
 * @param string $lang The course lang.
 * @return array
 */
function course_category(array $config, \stdClass $core_course_category, string $lang): array {

    $url = $config['app_url'] . '/course/index.php?categoryid=' . $core_course_category->id;
    $categoryname = property_exists($core_course_category, 'name') ? $core_course_category->name : 'Category';

    return [
        'id' => $url,
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/category',
            'name' => [
                $lang => $categoryname,
            ],
        ],
    ];

}
