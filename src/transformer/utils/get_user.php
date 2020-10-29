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

namespace src\transformer\utils;
defined('MOODLE_INTERNAL') || die();

function get_user(array $config, \stdClass $user) {
    $fullname = get_full_name($user);
    $repo = $config['repo'];
    $homePage = $config['app_url'];

    // The following email validation matches that in Learning Locker
    $hasvalidemail = mb_ereg_match("[A-Z0-9\\.\\`\\'_%+-]+@[A-Z0-9.-]+\\.[A-Z]{1,63}$", $user->email, "i");

    if (array_key_exists('send_mbox', $config) && $config['send_mbox'] == true && $hasvalidemail) {
        return [
            'name' => $fullname,
            'mbox' => 'mailto:' . $user->email,
        ];
    }

    // check if the value is set to use OAuth2 issuer as homePage
    if (array_key_exists('send_oauth2_issuer', $config) && $config['send_oauth2_issuer'] == true) {
        // check if this user is logged in via OAuth2
        if (isset($user->auth) && $user->auth == 'oauth2') {
            try {
                // find the oauth2 issuer that this user is logged in under
                $issuerid = $repo->read_record('auth_oauth2_linked_login', [
                    'userid' => $user->id
                ])->issuerid;   
                // get the issuer's baseurl         
                $issueridbaseurl = $repo->read_record_by_id('oauth2_issuer', $issuerid)->baseurl;
                if (isset($issueridbaseurl)) {
                    // if the baseurl is properly found, set the homePage to it
                    $homePage = $issueridbaseurl;
                }
            } catch (\Exception $e) {   }
        }
    }

    if (array_key_exists('send_username', $config) && $config['send_username'] === true) {
        return [
            'name' => $fullname,
            'account' => [
                'homePage' => $homePage,
                'name' => $user->username,
            ],
        ];
    }

    return [
        'name' => $fullname,
        'account' => [
            'homePage' => $homePage,
            'name' => strval($user->id),
        ],
    ];
}
