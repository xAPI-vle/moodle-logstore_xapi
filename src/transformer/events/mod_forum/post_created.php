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

namespace src\transformer\events\mod_forum;

defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

function post_created(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $user = $repo->read_record_by_id('user', $event->userid);
    $course = $repo->read_record_by_id('course', $event->courseid);
    $post = $repo->read_record_by_id('forum_posts', $event->objectid);
    $other = unserialize($event->other);
    $discussionid = $other['discussionid'];
    $discussion = $repo->read_record_by_id('forum_discussions', $discussionid);

    $lang = utils\get_course_lang($course);

    return[[
        'actor' => utils\get_user($config, $user),
        'verb' => [
            'id' => 'http://id.tincanapi.com/verb/replied',
            'display' => [
                $lang => 'replied to'
            ],
        ],
        'object' => utils\get_activity\course_discussion($config, $course, $discussion),
        'timestamp' => utils\get_event_timestamp($event),
        'result' => [
            'response' => utils\get_activity\forum_discussion_post_reply($config, $post)
        ],
        'context' => [
            'platform' => $config['source_name'],
            'language' => $lang,
            'extensions' => utils\extensions\base($config, $event, $course),
            'contextActivities' => [
                'grouping' => [
                    utils\get_activity\site($config),
                    utils\get_activity\course($config, $course),
                    utils\get_activity\course_forum($config, $course, $event->contextinstanceid)
                ],
                'other' => [
                    utils\get_activity\forum_discussion_post($config, $discussionid, $post),
                ],
                'category' => [
                    utils\get_activity\source($config),
                ]
            ],
        ]
    ]];
}
