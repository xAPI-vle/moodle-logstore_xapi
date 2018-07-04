<?php
namespace logstore_xapi\task;
defined('MOODLE_INTERNAL') || die();

use tool_log\log\manager;
use logstore_xapi\log\store;

class emit_task extends \core\task\scheduled_task {

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('taskemit', 'logstore_xapi');
    }

    /**
     * Do the job.
     * Throw exceptions on errors (the job will be retried).
     */
    public function execute() {
        global $DB;

        $manager = get_log_manager();
        $store = new store($manager);
        $events = $DB->get_records('logstore_xapi_log');
        $storereturn = $store->process_events($events);
        foreach (array_keys($storereturn) as $eventid) {
            if ($storereturn[$eventid] == 'success') {
                $DB->delete_records_list('logstore_xapi_log', 'id', array($eventid));
                mtrace("Event id ".$eventid." has been successfully sent to LRS.");
            }
        }

        mtrace("Sent learning records to LRS.");
    }
}
