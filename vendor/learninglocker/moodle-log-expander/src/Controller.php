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
        '\mod_book\event\course_module_viewed' => 'ModuleEvent',
        '\mod_scorm\event\course_module_viewed' => 'ModuleEvent',
        '\mod_quiz\event\attempt_preview_started' => 'AttemptEvent',
        '\mod_quiz\event\attempt_reviewed' => 'AttemptEvent',
        '\core\event\user_loggedin' => 'Event',
        '\core\event\user_loggedout' => 'Event',
        '\mod_assign\event\submission_graded' => 'AssignmentGraded',
        '\mod_assign\event\assessable_submitted' => 'AssignmentSubmitted',
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
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     */
    public function createEvent(array $opts) {
        $route = isset($opts['eventname']) ? $opts['eventname'] : '';
        if (isset(static::$routes[$route])) {
            $event = '\LogExpander\Events\\'.static::$routes[$route];
            return (new $event($this->repo))->read($opts);
        } else {
            return null;
        }
    }
}
