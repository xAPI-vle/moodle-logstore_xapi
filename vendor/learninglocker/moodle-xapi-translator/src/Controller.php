<?php namespace MXTranslator;
use \stdClass as PhpObj;

class Controller extends PhpObj {
    protected $repo;
    public static $routes = [
        '\core\event\course_viewed' => 'CourseViewed',
        '\mod_page\event\course_module_viewed' => 'ModuleViewed',
        '\mod_quiz\event\course_module_viewed' => 'ModuleViewed',
        '\mod_url\event\course_module_viewed' => 'ModuleViewed',
        '\mod_folder\event\course_module_viewed' => 'ModuleViewed',
        '\mod_book\event\course_module_viewed' => 'ModuleViewed',
        '\mod_quiz\event\attempt_preview_started' => 'AttemptStarted',
        '\mod_quiz\event\attempt_reviewed' => 'AttemptReviewed',
        '\core\event\user_loggedin' => 'UserLoggedin',
        '\core\event\user_loggedout' => 'UserLoggedout',
        '\mod_assign\event\submission_graded' => 'AssignmentGraded',
        '\mod_assign\event\assessable_submitted' => 'AssignmentSubmitted',
    ];

    /**
     * Constructs a new Controller.
     */
    public function __construct() {}

    /**
     * Creates a new event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     */
    public function createEvent(array $opts) {
        $route = isset($opts['event']['eventname']) ? $opts['event']['eventname'] : '';
        if (isset(static::$routes[$route])) {
            $event = '\MXTranslator\Events\\'.static::$routes[$route];
            return (new $event())->read($opts);
        } else {
            return null;
        }
    }
}
