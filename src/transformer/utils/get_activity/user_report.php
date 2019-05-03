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

namespace src\transformer\utils\get_activity;
defined('MOODLE_INTERNAL') || die();

use src\transformer\utils as utils;

function user_report(array $config, \stdClass $user, \stdClass $course, $courselang) {

    $activity = [
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/user-profile',
            'name' => [
                $courselang => 'forum posts of '.utils\get_full_name($user),
            ],
            'extensions' => [
                'https://moodle.org/xapi/extensions/user_id' => $user->id,
            ],
        ],
    ];

    if ($course->id == "0") {
        $activity['id'] = $config['app_url'].'/mod/forum/user.php?id='.$user->id;
    } else {
        $activity['id'] = $config['app_url'].'/mod/forum/user.php?id='.$user->id.'&course='.$course->id;
        $activity['definition']['extensions']['https://moodle.org/xapi/extensions/course_id'] = $course->id;
    }

    return $activity;
}
