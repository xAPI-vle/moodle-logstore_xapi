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

/**
 * Return the requested verb with details.
 *
 * @param string $verb requested verb
 * @param array $config Predefined config elements.
 * @param string $lang lang string
 * @return array
 * @throws \coding_exception
 */
function get_verb($verb, array $config, $lang) {

    $output = array();
    switch ($verb) {
        case 'completed':
            $output = [
                'id' => 'http://adlnet.gov/expapi/verbs/completed',
                'display' => [
                    $lang => 'completed'
                ],
            ];
            break;

        case 'submitted':
            $output = [
                'id' => 'http://activitystrea.ms/schema/1.0/submit',
                'display' => [
                    $lang => 'submitted'
                ],
            ];
            break;

        default:
            break;
    }

    if (empty($output)) {
        throw new \coding_exception(get_string('unknownverb', 'logstore_xapi'));
    }

    return $output;
}
