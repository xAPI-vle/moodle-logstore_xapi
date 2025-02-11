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
 * Transformer utility for info xAPI extensions.
 *
 * @package   logstore_xapi
 * @copyright Jerret Fowler <jerrett.fowler@gmail.com>
 *            Ryan Smith <https://www.linkedin.com/in/ryan-smith-uk/>
 *            David Pesce <david.pesce@exputo.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\extensions;

/**
 * Transformer utility extension information.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $event The event to be transformed.
 * @return array
 */
function info(array $config, \stdClass $event) {
    $repo = $config['repo'];
    $actorId = property_exists($event, 'relateduserid') &&
                isset($event->relateduserid) ? $event->relateduserid:$event->userid;
    $user = $repo->read_record_by_id('user', $actorId);
    $username = property_exists($user, 'username') &&
                isset($user->username) ? $user->username:"";
    $emailAddress = property_exists($user, 'email') &&
                isset($user->email) ? $user->email:"";
    return [
        'http://lrs.learninglocker.net/define/extensions/info' => [
            $config['source_url'] => $config['source_version'],
            $config['plugin_url'] => $config['plugin_version'],
            'event_name' => $event->eventname,
            'event_function' => $config['event_function'],
            'emailAddress' => $emailAddress,
            'username' => $username
        ],
    ];
}
