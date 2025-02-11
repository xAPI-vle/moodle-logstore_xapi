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
 * Transformer utility for retrieving SCORM activities.
 *
 * @package   logstore_xapi
 * @copyright Milt Reder <milt@yetanalytics.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity;

use src\transformer\utils as utils;

/**
 * Transformer utility for retrieving SCORM activities.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $course The course object.
 * @param int $cmid The id of the context.
 * @return array
 */
function scorm_content_object(array $config, \stdClass $course, int $cmid) {
    $repo = $config['repo'];
    $coursemodule = $repo->read_record_by_id('course_modules', $cmid);
    $module = $repo->read_record_by_id('modules', $coursemodule->module);
    $instance = $repo->read_record_by_id($module->name, $coursemodule->instance);

    $coursemoduleurl = $config['app_url'].'/mod/'.$module->name.'/view.php?id='.$cmid;
    $courselang = utils\get_course_lang($course);
    $instancename = property_exists($instance, 'name') ? $instance->name : $module->name;

    $activitytype = 'https://xapi.edlm/profiles/edlm-lms/concepts/activity-types/scorm-content-object';

    $def = [
        'type' => $activitytype,
        'name' => [
            $courselang => $instancename . ' Content',
        ],
    ];

    $object = [
        ...base(),
        'id' => $coursemoduleurl . '#sco',
        'definition' => $def,
    ];

    return $object;
}
