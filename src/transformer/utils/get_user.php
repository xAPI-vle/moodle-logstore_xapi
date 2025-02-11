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
function get_user(array $config, \stdClass $user) {

    $actor = array();

    // Name
    if (array_key_exists('send_name', $config) && $config['send_name'] == true) {
        $actor['name'] = get_full_name($user);
    }

    // mbox
    $hasvalidemail = filter_var($user->email, FILTER_VALIDATE_EMAIL);

    if (array_key_exists('send_mbox', $config) && $config['send_mbox'] == true && $hasvalidemail) {
        $actor['mbox'] = 'mailto:' . $user->email;
        return $actor;
    }

    // account

    if (array_key_exists('account_homepage', $config) && !empty($config['account_homepage'])) {
        $homePage = $config['account_homepage'];
    } else {
        $homePage = $config['app_url'];
    }

    if (array_key_exists('send_username', $config) && $config['send_username'] == true) {
        $actor['account'] = [
            'homePage' => $homePage,
            'name' => $user->username,
        ];
        return $actor;
    }

    // default
    $actor['account'] = [
        'homePage' => $config['app_url'],
        'name' => strval($user->id),
    ];
    return $actor;
}
