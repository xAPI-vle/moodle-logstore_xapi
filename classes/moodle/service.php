<?php namespace logstore_emitter\xapi;
use \core\event\base as base_event;
use \stdClass as php_obj;

class service extends php_obj {
    /**
     * Constructs a new service.
     * @param repository $repo The LRS to be used to store statements.
     */
    public function __construct(repository $repo) {
        $this->repo = $repo;
    }

    /**
     * Creates a new event.
     * @param [string => mixed] $opts
     * @return [string => mixed] Event
     */
    public function create(array $opts) {
        $opts['user'] = $this->read_user($opts['userid']);
        $opts['object'] = $this->read_object($opts);
        return $opts;
    }

    /**
     * Reads a user from the repository.
     * @param string $id User's Identifier.
     * @return php_obj
     */
    private function read_user($id) {
        global $CFG;
        $user = $this->repo->read_user($id);
        $user->url = $CFG->wwwroot . '/user/profile.php?id=' . $id;
        return $user;
    }

    /**
     * Reads a object from the restored event.
     * @param [string => mixed] $opts
     * @return php_obj
     */
    private function read_object(array $opts) {
        $restored_url = $this->restore_event($opts)->get_url();
        return (object) [
            'id' => $restored_url->getParam('id'),
            'url' => $this->generate_url($restored_url)
        ];
    }

    /**
     * Generates the object URL from the given URL.
     * @param php_obj $url
     * @return string URL
     */
    private function generate_url(php_obj $url) {
        return $url->get_scheme() . "://" . $url->get_host() . $url->get_path() . '?id=' . $url->get_param('id');
    }

    /**
     * Restores an event.
     * @param [string => mixed] $event
     * @return string
     */
    private function restore_event(array $event) {
        $data = [
            'eventname' => $event['eventname'],
            'component' => $event['component'],
            'action' => $event['action'],
            'target' => $event['target'],
            'objecttable' => $event['objecttable'],
            'objectid' => $event['objectid'],
            'crud' => $event['crud'],
            'edulevel' => $event['edulevel'],
            'contextid' => $event['contextid'],
            'contextlevel' => $event['contextlevel'],
            'contextinstanceid' => $event['contextinstanceid'],
            'userid' => $event['userid'],
            'courseid' => $event['courseid'],
            'relateduserid' => $event['relateduserid'],
            'anonymous' => $event['anonymous'],
            'other' => $event['other'],
            'timecreated' => $event['timecreated']
        ];

        $logextra = [
            'origin' => $event['origin'],
            'ip' => $event['ip'],
            'realuserid' => $event['realuserid']
        ];

        return base_event::restore($data, $logextra);
    }
}
