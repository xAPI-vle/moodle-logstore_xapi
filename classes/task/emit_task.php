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
        $conditions = null;
        $sort = '';
        $fields = '*';
        $limitfrom = 0; 
        $limitnum = $store->get_max_batch_size();
        $extractedevents = $DB->get_records('logstore_xapi_log', $conditions, $sort, $fields, $limitfrom, $limitnum);
        $loadedevents = $store->process_events($extractedevents);
        $loadedeventids = array_map(function ($transformedevent) {
            return $transformedevent['eventid'];
        }, $loadedevents);
        $DB->delete_records_list('logstore_xapi_log', 'id', $loadedeventids);
        mtrace("Events (".implode(', ', $loadedeventids).") have been successfully sent to LRS.");
    }
}
