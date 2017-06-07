<?php namespace LogExpander;
use \stdClass as PhpObj;
class Controller extends PhpObj {
    protected $repo;
    public static $routes = [
        '\core\event\course_viewed' => 'Event',
        '\mod_page\event\course_module_viewed' => 'ModuleEvent',
        '\mod_quiz\event\course_module_viewed' => 'ModuleEvent',
        '\mod_url\event\course_module_viewed' => 'ModuleEvent',
        '\mod_folder\event\course_module_viewed' => 'ModuleEvent',
        '\mod_forum\event\course_module_viewed' => 'ModuleEvent',
        '\mod_forum\event\discussion_viewed' => 'DiscussionEvent',
        '\mod_forum\event\user_report_viewed' =>  'ModuleEvent',
        '\mod_book\event\course_module_viewed' => 'ModuleEvent',
        '\mod_scorm\event\course_module_viewed' => 'ModuleEvent',
        '\mod_resource\event\course_module_viewed' => 'ModuleEvent',
        '\mod_choice\event\course_module_viewed' => 'ModuleEvent',
        '\mod_data\event\course_module_viewed' => 'ModuleEvent',
        '\mod_feedback\event\course_module_viewed' => 'ModuleEvent',
        '\mod_lesson\event\course_module_viewed' => 'ModuleEvent',
        '\mod_lti\event\course_module_viewed' => 'ModuleEvent',
        '\mod_wiki\event\course_module_viewed' => 'ModuleEvent',
        '\mod_workshop\event\course_module_viewed' => 'ModuleEvent',
        '\mod_chat\event\course_module_viewed' => 'ModuleEvent',
        '\mod_glossary\event\course_module_viewed' => 'ModuleEvent',
        '\mod_imscp\event\course_module_viewed' => 'ModuleEvent',
        '\mod_survey\event\course_module_viewed' => 'ModuleEvent',
        '\mod_facetoface\event\course_module_viewed' => 'ModuleEvent',
        '\mod_quiz\event\attempt_abandoned' => 'AttemptEvent',
        '\mod_quiz\event\attempt_preview_started' => 'AttemptEvent',
        '\mod_quiz\event\attempt_reviewed' => 'AttemptEvent',
        '\mod_quiz\event\attempt_viewed' => 'AttemptEvent',
        '\core\event\user_loggedin' => 'Event',
        '\core\event\user_loggedout' => 'Event',
        '\mod_assign\event\submission_graded' => 'AssignmentGraded',
        '\mod_assign\event\assessable_submitted' => 'AssignmentSubmitted',
        '\core\event\user_created' => 'Event',
        '\core\event\user_enrolment_created' => 'Event',
        '\mod_scorm\event\sco_launched' => 'ScormLaunched',
        '\mod_feedback\event\response_submitted' => 'FeedbackSubmitted',
        '\mod_facetoface\event\signup_success' => 'FacetofaceEvent',
        '\mod_facetoface\event\cancel_booking' => 'FacetofaceEvent',
        '\mod_facetoface\event\take_attendance' => 'FacetofaceAttended',
        '\core\event\course_completed'=>'CourseCompleted',
        '\core\event\course_module_completion_updated' =>'CourseModuleCompleted'
    ];
    /**
     * Constructs a new Controller.
     * @param Repository $repo
     */
    public function __construct(Repository $repo) {
        $this->repo = $repo;
    }
    /**
     * Creates new events.
     * @param [String => Mixed] $events
     * @return [String => Mixed]
     */
    public function createEvents(array $events) {
        $results = [];
        foreach ($events as $index => $opts) {
            $route = isset($opts['eventname']) ? $opts['eventname'] : '';
            if (isset(static::$routes[$route]) && ($opts['userid'] > 0 || $opts['relateduserid'] > 0)) {
                try {
                    $event = '\LogExpander\Events\\'.static::$routes[$route];
                    array_push($results , (new $event($this->repo))->read($opts));
                }
                catch (\Exception $e) {
                    // Error processing event; skip it.
                }
            }
        }
        return $results;
    }
}
