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
 * Transformer utility for retrieving (forum discussion subscription) activities.
 *
 * @package   logstore_xapi
 * @copyright 2023 Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity;

/**
 * Transformer utility for retrieving (forum discussion subscription) activities.
 *
 * @param array $config The transformer config settings.
 * @param string $lang The language of the badge.
 * @param int $forumid The id of the forum.
 * @param \stdClass $discussion The discussion object.
 * @return array
 */

function forum_discussion_subscription(array $config, string $lang, int $forumid, \stdClass $discussion): array {

    $url = $config['app_url'] . '/mod/forum/subscribe.php?id=' . $forumid . '&d=' . $discussion->id;
    $discussionname = property_exists($discussion, 'name') ? $discussion->name : 'Discussion';

    return [
        'id' => $url,
        'definition' => [
            'type' => 'http://vocab.xapi.fr/activities/registration',
            'name' => [
                $lang => $discussionname,
            ],
        ],
    ];
}
