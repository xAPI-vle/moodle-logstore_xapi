<?php

namespace transformer;

function handler(array $config, array $events) {
    $event_function_map = [
        '\core\event\course_completed' => 'core\course_completed',
        '\core\event\course_viewed' => 'core\course_viewed',
        '\core\event\user_created' => 'core\user_created',
        '\core\event\user_enrolment_created' => 'core\user_enrolment_created',
        '\core\event\user_loggedin' => 'core\user_loggedin',
        '\core\event\user_loggedout' => 'core\user_loggedout',
        '\mod_assign\event\assessable_submitted' => 'mod_assign\assignment_submitted',
        '\mod_assign\event\submission_graded' => 'mod_assign\assignment_graded',
        '\mod_book\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_chat\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_choice\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_data\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_facetoface\event\cancel_booking' => 'mod_facetoface\cancel_booking',
        '\mod_facetoface\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_facetoface\event\signup_success' => 'mod_facetoface\signup_success',
        '\mod_facetoface\event\take_attendance' => 'mod_facetoface\take_attendance',
        '\mod_feedback\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_feedback\event\response_submitted' => 'mod_feedback\response_submitted',
        '\mod_folder\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_forum\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_forum\event\discussion_viewed' => 'mod_forum\discussion_viewed',
        '\mod_forum\event\user_report_viewed' => 'all\course_module_viewed',
        '\mod_glossary\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_imscp\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_lesson\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_lti\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_page\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_quiz\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_quiz\event\attempt_abandoned' => 'mod_quiz\attempt_submitted',
        '\mod_quiz\event\attempt_started' => 'mod_quiz\attempt_started',
        '\mod_quiz\event\attempt_reviewed' => 'mod_quiz\attempt_reviewed',
        '\mod_quiz\event\attempt_submitted' => 'mod_quiz\attempt_submitted',
        '\mod_quiz\event\attempt_viewed' => 'all\course_module_viewed',
        '\mod_resource\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_scorm\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_scorm\event\sco_launched' => 'mod_scorm\sco_launched',
        '\mod_scorm\event\scoreraw_submitted' => 'mod_scorm\scoreraw_submitted',
        '\mod_scorm\event\status_submitted' => 'mod_scorm\status_submitted',
        '\mod_survey\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_url\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_wiki\event\course_module_viewed' => 'all\course_module_viewed',
        '\mod_workshop\event\course_module_viewed' => 'all\course_module_viewed',
    ];
    return array_reduce($events, function ($statements, $event) use ($config, $event_function_map) {
        $event_name = $event->eventname;
        $event_function_name = $event_function_map[$event_name];
        $event_function = '\transformer\events\\' . $event_function_name;
        $event_config = array_merge($config, [
            'event_function' => $event_function,
        ]);
        $event_statements = $event_function($event_config, $event);
        return array_merge($statements, $event_statements);
    }, []);
}