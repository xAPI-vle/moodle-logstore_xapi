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
 * List of other events.
 *
 * @package   logstore_xapi
 * @copyright Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\event_functions;

/**
 * Return a list of events whose educational level is other.
 *
 * @return array
 */

function other(): array {

    return [

        // Core.
        '\core\event\course_category_viewed' => 'core\course_category_viewed',
        '\core\event\group_message_sent' => 'core\group_message_sent',
        '\core\event\message_deleted' => 'core\message_deleted',
        '\core\event\message_sent' => 'core\message_sent',
        '\core\event\message_viewed' => 'core\message_viewed',



        // Assign.
        '\mod_assign\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Attendance.
        '\mod_attendance\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Book.
        '\mod_book\event\course_module_instance_list_viewed' => 'all\event\course_module_instance_list_viewed',

        // Chat.
        '\mod_chat\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Checklist.
        '\mod_checklist\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Choice.
        '\mod_choice\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Choice group.
        '\mod_choicegroup\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Database.
        '\mod_data\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Feedback.
       '\mod_feedback\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Folder.
       '\mod_folder\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Forum.
        '\mod_forum\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Glossary.
        '\mod_glossary\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // H5P activity.
        '\mod_h5pactivity\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Imscp.
        '\mod_imscp\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Lesson.
        '\mod_lesson\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Lti.
        '\mod_lti\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Page.
        '\mod_page\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Quesionnaire.
        '\mod_questionnaire\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Quiz.
        '\mod_quiz\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Resource.
        '\mod_resource\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Scheduler.
        '\mod_scheduler\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Scorm.
        '\mod_scorm\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Survey.
        '\mod_survey\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // URL.
        '\mod_url\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Wiki.
        '\mod_wiki\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Wooclap.
        '\mod_wooclap\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

        // Workshop.
        '\mod_workshop\event\course_module_instance_list_viewed' => 'all\course_module_instance_list_viewed',

    ];

}
