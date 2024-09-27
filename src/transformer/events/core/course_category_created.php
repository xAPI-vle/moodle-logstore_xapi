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
 * Transform for the course category created event.
 *
 * @package   logstore_xapi
 * @copyright Milt Reder <milt@yetanalytics.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\events\core;

use src\transformer\utils as utils;

/**
 * Helper for making course category objects.
 *
 * @param array $config The configuration object.
 * @param \stdClass $category The course category.
 * @return array
 */

function cc_object(array $config, \stdClass $category) {
    $lang = $config['source_lang'];
    return [
        'id' => $config['app_url'] . '/course/management.php?categoryid=' . $category->id,
        'objectType' => 'Activity',
        'definition' => [
            'name' => [
                $lang => $category->name,
            ],
            'description' => [
                $lang => $category->description,
            ],
            'type' => 'https://xapi.edlm/profiles/edlm-lms/concepts/activity-types/course-category',
        ],
    ];
}

/**
 * Transformer for course category created event.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $event The event to be transformed.
 * @return array
 */
function course_category_created(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->userid);
    $lang = $config['source_lang'];
    $category = $repo->read_record_by_id($event->objecttable, $event->objectid);
    $parent_category = $repo->read_record_by_id($event->objecttable, $category->parent);

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'http://activitystrea.ms/create',
            'display' => [
                $lang => 'Created'
            ],
        ],
        'object' => cc_object($config, $category),
        'context' => [
            'extensions' => utils\extensions\base($config, $event, null),
            'contextActivities' => [
                'parent' => [
                    cc_object($config, $parent_category),
                ],
                'category' => [
                    utils\get_activity\site($config),
                ],
            ],
        ]
    ]];
}
