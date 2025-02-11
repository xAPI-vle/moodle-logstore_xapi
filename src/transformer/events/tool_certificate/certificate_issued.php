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
 * Transform for certificate issued event.
 *
 * @package   logstore_xapi
 * @copyright Milt Reder <milt@yetanalytics.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\events\tool_certificate;

use src\transformer\utils as utils;

/**
 * Transforms certificate issued event to an "Achieved" xapi event
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $event The event to be transformed.
 * @return array
 */
function certificate_issued(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->relateduserid);
    $issuer = $repo->read_record_by_id('user', $event->userid);
    $issue = $repo->read_record_by_id('tool_certificate_issues', $event->objectid);
    $course = (!is_null($issue->courseid))
        ? $repo->read_record_by_id('course', $issue->courseid)
        : null;
    $lang = is_null($course)
        ? $config['source_lang']
    : utils\get_course_lang($course);

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'https://w3id.org/xapi/tla/verbs/achieved',
            'display' => [
                'en' => 'Achieved'
            ],
        ],
        'object' => utils\get_activity\certificate(
            $config,
            $issue->code
        ),
        'context' => [
            ...utils\get_context_base($config, $event, $lang, $course),
            'instructor' => utils\get_user($config, $issuer),
            'contextActivities' => [
                ...(
                    is_null($course)
                        ? []
                        : [
                            'parent' => [
                                utils\get_activity\course(
                                    $config,
                                    $course
                                ),
                            ],
                        ]
                ),
                'category' => [
                    utils\get_activity\site($config),
                ],
            ],
        ],
    ]];
}
