<?php namespace logstore_emitter\moodle;
use \core\event\base as base_event;
use \stdClass as php_obj;

class repository extends php_obj {
    protected $store;
    protected $cfg;

    /**
     * Constructs a new repository.
     * @param $store
     * @param php_obj $cfg
     */
    public function __construct($store, php_obj $cfg) {
        $this->store = $store;
        $this->cfg = $cfg;
    }

    /**
     * Reads an object from the store with the given id.
     * @param string $id
     * @param string $type
     * @return php_obj
     */
    public function read_object($id, $type) {
        $model = $this->store->get_record($type, ['id' => $id]);
        return $model;
    }

    /**
     * Reads an object from the store with the given id.
     * @param string $id
     * @param string $type
     * @return php_obj
     */
    public function read_module($id, $type) {
        $model = $this->read_object($id, $type);
        $module = $this->store->get_record('modules', ['name' => $type]);
        $course_module = $this->store->get_record('course_modules', [
            'instance' => $id,
            'module' => $module->id,
            'course' => $model->course
        ]);
        $model->url = $this->cfg->wwwroot . '/mod/'.$type.'/view.php?id=' . $course_module->id;
        return $model;
    }

    /**
     * Reads a course from the store with the given id.
     * @param string $id
     * @return php_obj
     */
    public function read_course($id) {
        $model = $this->read_object($id, 'course');
        $model->url = $this->cfg->wwwroot . '/course.php?id=' . $id;
        return $model;
    }

    /**
     * Reads a user from the store with the given id.
     * @param string $id
     * @return php_obj
     */
    public function read_user($id) {
        $model = $this->read_object($id, 'user');
        $model->url = $this->cfg->wwwroot;
        return $model;
    }

    /**
     * Reads the event url.
     * @param [string => mixed] $opts
     * @return string
     */
    public function read_event_url(array $opts) {
        $restored_url = $this->restore_event($opts)->get_url();
        return $this->generate_url($restored_url);
    }

    /**
     * Generates the object URL from the given URL.
     * @param $url
     * @return string URL
     */
    private function generate_url($url) {
        return $url->get_scheme() . "://" . $url->get_host() . $url->get_path() . '?id=' . $url->get_param('id');
    }

    /**
     * Restores an event.
     * @param [string => mixed] $opts
     * @return php_obj
     */
    private function restore_event(array $opts) {
        $data = [
            'eventname' => $opts['eventname'],
            'component' => $opts['component'],
            'action' => $opts['action'],
            'target' => $opts['target'],
            'objecttable' => $opts['objecttable'],
            'objectid' => $opts['objectid'],
            'crud' => $opts['crud'],
            'edulevel' => $opts['edulevel'],
            'contextid' => $opts['contextid'],
            'contextlevel' => $opts['contextlevel'],
            'contextinstanceid' => $opts['contextinstanceid'],
            'userid' => $opts['userid'],
            'courseid' => $opts['courseid'],
            'relateduserid' => $opts['relateduserid'],
            'anonymous' => $opts['anonymous'],
            'other' => $opts['other'],
            'timecreated' => $opts['timecreated']
        ];

        $logextra = [
            'origin' => $opts['origin'],
            'ip' => $opts['ip'],
            'realuserid' => $opts['realuserid']
        ];

        return base_event::restore($data, $logextra);
    }
}
