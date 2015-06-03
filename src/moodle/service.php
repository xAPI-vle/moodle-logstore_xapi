<?php namespace logstore_emitter\xapi;
use \stdClass as php_obj;

class service extends php_obj {
    /**
     * Constructs a new service.
     */
    public function __construct() {}

    /**
     * Creates a new event.
     * @param [string => mixed] $opts
     * @return [string => mixed] Event
     */
    public function create(array $opts) {
        // Constructs the user.
        global $CFG;
        $user_id = $opts['userid'];
        $user_url = $CFG->wwwroot . '/user/profile.php?id=' . $user_id;
        $opts['user'] = new user($user_id, $user_url);

        // Constructs the object.
        $restored_url = $this->restore_event($opts)->get_url();
        $object_id = $restored_url->getParam('id');
        $object_url = $this->generate_url($restored_url);
        $opts['object'] = new object($object_id, $object_url);

        return $opts;
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

        return \core\event\base::restore($data, $logextra);
    }
}
