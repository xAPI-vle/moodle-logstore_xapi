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
 * Transform for the answer deleted event.
 *
 * @package   logstore_xapi
 * @copyright 2023 Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\events\mod_choice;

use src\transformer\utils as utils;

/**
 * Transformer for answer deleted event.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $event The event to be transformed.
 * @return array
 */

function answer_deleted(array $config, \stdClass $event): array {

    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->userid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $other = unserialize($event->other);
    $choiceid = $other['choiceid'];
    $choice = $repo->read_record_by_id('choice', $choiceid);
    $optionid = $other['optionid'];
    $response = $repo->read_record_by_id('choice_options', $optionid);
    $cmid = $event->contextinstanceid;
    $lang = utils\get_course_lang($course);

    return [[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'http://activitystrea.ms/schema/1.0/delete',
            'display' => [
                $lang => 'deleted'
            ],
        ],
        'object' =>  utils\get_activity\choice($config, $cmid, $lang, $choice),
        'timestamp' => utils\get_event_timestamp($event),
        'result' => [
            'response' => $response->text,
            ],
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => utils\extensions\base($config, $event, $course),
            'contextActivities' => [
                'grouping' => [
                    utils\get_activity\site($config),
                    utils\get_activity\course($config, $course),
                    utils\get_activity\course_module(
                        $config,
                        $course,
                        $event->contextinstanceid,
                        'http://vocab.xapi.fr/activities/poll'
                    ),
                ],
                'category' => [
                    utils\get_activity\source($config),
                ]
            ],
        ]
    ]];
}
