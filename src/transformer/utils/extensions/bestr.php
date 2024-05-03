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
 * Transformer utility for Bestr xAPI extensions.
 *
 * @package   logstore_xapi
 * @copyright CINECA
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\extensions;

use src\transformer\utils as utils;

/**
 * Transformer utility for base xAPI extensions - Bestr specific.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $event The event to be transformed.
 * @param object $course The course object.
 * @return array
 */
function bestr(array $config, \stdClass $event, $course) {
    if (utils\is_enabled_config($config, 'send_bestr_data')) {
        $repo = $config['repo'];
        if (isset($event->relateduserid) && $event->relateduserid) {
            $user = $repo->read_record_by_id('user', $event->relateduserid);
        } else {
            $user = $repo->read_record_by_id('user', $event->userid);
        }

        profile_load_data($user);   // Load custom profile fields.
        $birthdatefieldname = $config['bestr_custom_birthdate'];
        $cffieldname = $config['bestr_custom_cf'];

        $extrafields = array();
        // If the field name is specified, if the user field exists and if it's full, then use it.
        if ($birthdatefieldname && isset($user->$birthdatefieldname) && $user->$birthdatefieldname) {
            $extrafields["actor_birthday"] = $user->$birthdatefieldname;
        }
        if ($cffieldname && isset($user->$cffieldname) && $user->$cffieldname) {
            $extrafields["actor_cf"] = $user->$cffieldname;
        }

        return array(
            'http://lrs.bestr.it/lrs/define/context/extensions/actor' => array_merge(array(
                    "actor_name" => $user->firstname,
                    "actor_surname" => $user->lastname
                    ), $extrafields     // If extra fields are present, they are added here.
                )
            );
    }
    return [];
}
