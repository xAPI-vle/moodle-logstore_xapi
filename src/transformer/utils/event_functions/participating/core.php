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
 * List of participating core events.
 *
 * @package   logstore_xapi
 * @copyright Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\event_functions\participating;

function core(): array {

    return [

        // Core.
        '\core\event\badge_listing_viewed' => 'core\badge_listing_viewed', // NEW
        '\core\event\badge_viewed' => 'core\badge_viewed', // NEW
        // '\core\event\course_category_viewed' => 'core\course_category_viewed',
        '\core\event\course_completed' => 'core\course_completed',
        // '\core\event\course_completion_updated' => 'core\course_completion_updated',
        // '\core\event\course_information_viewed' => 'core\course_information_viewed',
        '\core\event\course_module_completion_updated' => 'core\course_module_completion_updated',
        // '\core\event\courses_searched' => 'core\courses_searched',
        // '\core\event\course_user_report_viewed' => 'core\course_user_report_viewed',
        '\core\event\course_viewed' => 'core\course_viewed',
        '\core\event\dashboard_viewed' => 'core\dashboard_viewed', // TODO create activity.
        // '\core\event\dashboard_reset' => 'core\dashboard_reset',
        // '\core\event\dashboards_reset' => 'core\dashboards_reset',
        // '\core\event\group_message_sent' => 'core\group_message_sent',
        '\core\event\message_deleted' => 'core\message_deleted', // NEW
        '\core\event\message_sent' => 'core\message_sent', // NEW
        '\core\event\message_viewed' => 'core\message_viewed', // NEW
        '\core\event\role_assigned' => 'core\role_assigned', // NEW
        '\core\event\role_unassigned' => 'core\role_unassigned', // NEW
        '\core\event\user_created' => 'core\user_created',
        '\core\event\user_deleted' => 'core\user_deleted', // NEW
        '\core\event\user_enrolment_created' => 'core\user_enrolment_created',
        '\core\event\user_enrolment_deleted' => 'core\user_enrolment_deleted', // NEW
        '\core\event\user_enrolment_updated' => 'core\user_enrolment_updated', // NEW
        '\core\event\user_loggedin' => 'core\user_loggedin',
        '\core\event\user_loggedout' => 'core\user_loggedout',
    ];
}
