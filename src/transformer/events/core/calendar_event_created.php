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
 * Transform for course viewed event.
 *
 * @package   logstore_xapi
 * @copyright Jerret Fowler <jerrett.fowler@gmail.com>
 *            Ryan Smith <https://www.linkedin.com/in/ryan-smith-uk/>
 *            David Pesce <david.pesce@exputo.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\events\core;

use src\transformer\utils as utils;

/**
 * Transformer for Calendar Event Created.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $event The event to be transformed.
 * @return array
 */
function calendar_event_created(array $config, \stdClass $event) {

    //debug
    $repo = $config['repo'];
    $event_object = $repo->read_record_by_id($event->objecttable, $event->objectid);

    return [[
        'object' => [
            'id' => 'http://www.yetanalytics.com/test_events/calendar_event_created'
        ],
        'actor' => [
            'mbox' => 'mailto:tester@example.com'
        ],
        'verb' => [
            'id' => 'http://www.yetanalytics.com/debug',
            'display' => [
                'en-US' => 'debug'
            ],
        ],
        'context' => [
            'extensions' => [
                'http://www.yetanalytics.com/debug_objects/event' => $event,
                'http://www.yetanalytics.com/debug_objects/event_object' => $event_object
            ]
        ]
    ]];
}
