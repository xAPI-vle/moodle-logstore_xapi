<?php namespace MXTranslator;
use \stdClass as PhpObj;

class Controller extends PhpObj {
    protected $repo;
    public static $routes = [
        '\core\event\course_viewed' => 'CourseViewed',
        '\core\event\course_completed' => 'CourseCompleted',
        '\core\event\course_module_completion_updated' =>'CourseModuleCompleted',
        '\mod_page\event\course_module_viewed' => 'ModuleViewed',
        '\mod_quiz\event\course_module_viewed' => 'ModuleViewed',
        '\mod_url\event\course_module_viewed' => 'ModuleViewed',
        '\mod_folder\event\course_module_viewed' => 'ModuleViewed',
        '\mod_forum\event\course_module_viewed' => 'ModuleViewed',
        '\mod_forum\event\discussion_viewed' =>  'DiscussionViewed',
        '\mod_forum\event\user_report_viewed' =>  'ModuleViewed',
        '\mod_book\event\course_module_viewed' => 'ModuleViewed',
        '\mod_scorm\event\course_module_viewed' => 'ModuleViewed',
        '\mod_resource\event\course_module_viewed' => 'ModuleViewed',
        '\mod_choice\event\course_module_viewed' => 'ModuleViewed',
        '\mod_data\event\course_module_viewed' => 'ModuleViewed',
        '\mod_feedback\event\course_module_viewed' => 'ModuleViewed',
        '\mod_lesson\event\course_module_viewed' => 'ModuleViewed',
        '\mod_lti\event\course_module_viewed' => 'ModuleViewed',
        '\mod_wiki\event\course_module_viewed' => 'ModuleViewed',
        '\mod_workshop\event\course_module_viewed' => 'ModuleViewed',
        '\mod_chat\event\course_module_viewed' => 'ModuleViewed',
        '\mod_glossary\event\course_module_viewed' => 'ModuleViewed',
        '\mod_imscp\event\course_module_viewed' => 'ModuleViewed',
        '\mod_survey\event\course_module_viewed' => 'ModuleViewed',
        '\mod_facetoface\event\course_module_viewed' => 'ModuleViewed',
        '\mod_quiz\event\attempt_abandoned' => 'AttemptAbandoned',
        '\mod_quiz\event\attempt_preview_started' => 'AttemptStarted',
        '\mod_quiz\event\attempt_reviewed' => ['AttemptReviewed', 'QuestionSubmitted'],
        '\mod_quiz\event\attempt_viewed' => 'ModuleViewed',
        '\core\event\user_loggedin' => 'UserLoggedin',
        '\core\event\user_loggedout' => 'UserLoggedout',
        '\mod_assign\event\submission_graded' => 'AssignmentGraded',
        '\mod_assign\event\assessable_submitted' => 'AssignmentSubmitted',
        '\core\event\user_created' => 'UserRegistered',
        '\core\event\user_enrolment_created' => 'EnrolmentCreated',
        '\mod_scorm\event\sco_launched' => 'ScormLaunched',
        '\mod_feedback\event\response_submitted' => ['FeedbackSubmitted','FeedbackQuestionSubmitted'],
        '\mod_facetoface\event\signup_success' => 'FacetofaceEnrol',
        '\mod_facetoface\event\cancel_booking' => 'FacetofaceUnenrol',
        '\mod_facetoface\event\take_attendance' => 'FacetofaceAttend',
        '\mod_scorm\event\scoreraw_submitted' => 'ScormScoreRawSubmitted',
        '\mod_scorm\event\status_submitted' => 'ScormStatusSubmitted'
    ];

    /**
     * Constructs a new Controller.
     */
    public function __construct() {}

    /**
     * Creates a new event.
     * @param [String => Mixed] $events
     * @return [String => Mixed]
     */
    public function createEvents(array $events) {
        $results = [];
        foreach ($events as $index => $opts) {
            $route = isset($opts['event']['eventname']) ? $opts['event']['eventname'] : '';
            if (isset(static::$routes[$route])) {
                    $routeEvents = is_array(static::$routes[$route]) ? static::$routes[$route] : [static::$routes[$route]];
                    foreach ($routeEvents as $routeEvent) {
                    try {
                        $event = '\MXTranslator\Events\\'.$routeEvent;
                        foreach ((new $event())->read($opts) as $index => $result) {
                             array_push($results, $result);
                         }
                    } catch (UnnecessaryEvent $ex) {}
                }
            }
        }
        return $results;
    }
}
