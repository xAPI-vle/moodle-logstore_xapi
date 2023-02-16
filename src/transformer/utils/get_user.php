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
 * Transformer utility for retrieving user data.
 *
 * @package   logstore_xapi
 * @copyright Jerret Fowler <jerrett.fowler@gmail.com>
 *            Ryan Smith <https://www.linkedin.com/in/ryan-smith-uk/>
 *            David Pesce <david.pesce@exputo.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils;

/**
 * Transformer utility for retrieving user data.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $user The user object.
 * @return array
 */
function get_user(array $config, \stdClass $user): array
{
    $fullname = get_full_name($user);
    $username = $user->username;
    $userid = strval($user->id);
    $email = $user->email;

    $hasvalidemail = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (array_key_exists('send_pseudo', $config) && $config['send_pseudo']) {
        $fullname = sha1($fullname);
        $username = sha1($username);
        $userid = sha1($userid);
        $email = sha1($email);
    }

    if (array_key_exists('send_mbox', $config) && $config['send_mbox'] && $hasvalidemail) {
        if((array_key_exists('send_pseudo', $config) && $config['send_pseudo'])){

            return [
                'name' => $fullname,
                'mbox_sha1sum' => $email,
            ];

        }

        else {
            return [
                'name' => $fullname,
                'mbox' => 'mailto:' . $email,
            ];
        }
    }



    if (array_key_exists('send_username', $config) && $config['send_username']) {

        return [
            'name' => $fullname,
            'account' => [
                'homePage' => $config['app_url'],
                'name' => $username,
            ],
        ];
    }


    return [
        'name' => $fullname,
        'account' => [
            'homePage' => $config['app_url'],
            'name' => $userid,
        ],
    ];
}

