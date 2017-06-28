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

namespace XREmitter;

defined('MOODLE_INTERNAL') || die();

use \stdClass as PhpObj;

class Controller extends PhpObj {
    protected $repo;
    public static $routes = [
        'course_viewed' => 'CourseViewed',
        'course_completed' => 'CourseCompleted',
        'discussion_viewed' => 'DiscussionViewed',
        'module_viewed' => 'ModuleViewed',
        'attempt_started' => 'AttemptStarted',
        'attempt_abandoned' => 'AttemptCompleted',
        'attempt_completed' => 'AttemptCompleted',
        'attempt_question_completed' => 'QuestionAnswered',
        'user_loggedin' => 'UserLoggedin',
        'user_loggedout' => 'UserLoggedout',
        'assignment_graded' => 'AssignmentGraded',
        'assignment_submitted' => 'AssignmentSubmitted',
        'user_registered' => 'UserRegistered',
        'enrolment_created' => 'EnrolmentCreated',
        'scorm_launched' => 'ScormLaunched',
        'training_session_attend' => 'Attended',
        'training_session_enrol' => 'EventEnrol',
        'training_session_unenrol' => 'EventUnenrol',
        'scorm_scoreraw_submitted' => 'ScormScoreRawSubmitted',
        'scorm_status_submitted' => 'ScormStatusSubmitted'
    ];

    /**
     * Constructs a new Controller.
     * @param Repository $repo
     */
    public function __construct(Repository $repo) {
        $this->repo = $repo;
    }

    /**
     * Creates a new event.
     * @param [String => Mixed] $events
     * @return [String => Mixed]
     */
    public function create_events(array $events) {
        $statements = [];
        foreach ($events as $index => $opts) {
            $route = isset($opts['recipe']) ? $opts['recipe'] : '';
            if (isset(static::$routes[$route])) {
                $event = '\XREmitter\Events\\'.static::$routes[$route];
                $service = new $event();
                $opts['context_lang'] = $opts['context_lang'] ?: 'en';
                array_push($statements, $service->read($opts));
            }
        }
        return $this->repo->create_events($statements);
    }
}
