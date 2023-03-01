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
 * Transformer utility for retrieving team members data.
 *
 * @package   logstore_xapi
 * @copyright Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

namespace src\transformer\utils;

/**
 * Transformer utility for retrieving team members data.
 *
 * @param array $config The transformer config settings.
 * @param int $cmid The id of the conversation.
 * @return array
 */
function get_team(array $config, int $cmid): array {

    $repo = $config['repo'];
    $messages = $repo->read_record_by_id('messages', $cmid);
    $senderid = $messages->useridfrom;
    $conversation = $repo->read_record_by_id('message_conversations', $messages->conversationid);
    $group = $repo->read_record_by_id('groups', $conversation->itemid);
    $members = $repo->read_records('groups_members', ['groupid' => $group->id]);

    $users = [];
    foreach ($members as $member){
        $user = $repo->read_record_by_id('user', $member->userid);
        if ($member->userid != $senderid) {
            $users[] = get_user($config, $user);
        }
    }

    return [
        'name' => $conversation->name,
        'account' => [
            'homePage' => 'http://localhost:8888/moodle39/group/index.php?id=' . $group->courseid .'&group=' . $conversation->itemid,
            'name' => 'GroupAccount' ,
        ],
        'objectType' => 'Group',
        'member' => $users
    ];
}
