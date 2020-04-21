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
/**
 * Process events.
 *
 * @package     logstore_xapi
 * @subpackage  log
 * @author      László Záborski <zaborski.laszlo@gmail.com> (http://www.zabo.hu)
 * @copyright   2020 Learning Pool Ltd (http://learningpool.com)
 */

namespace logstore_xapi\log;

defined('MOODLE_INTERNAL') || die();

/**
 * Class for processing events from a given events id list.
 *
 *
 *
 * @package     logstore_xapi
 * @subpackage  log
 * @author      László Záborski <zaborski.laszlo@gmail.com> (http://www.zabo.hu)
 * @copyright   2020 Learning Pool Ltd (http://learningpool.com)
 */
class process {

    /**
     * logstore database names.
     */
    const LOGSTORE_NEW = "logstore_xapi_log";
    const LOGSTORE_FAILED = "logstore_xapi_failed_log";

    /**
     * List of event IDs.
     *
     * @var array event ids[]
     */
    protected $eventids = array();

    /**
     * The table where the process works
     *
     * @var string
     */
    protected $table = '';

    /**
     * A list containing the constructed sql fragment.
     *
     * @var string
     */
    protected $select = '1=1';

    /**
     * An array of parameters.
     *
     * @var string
     */
    protected $params = array();

    /**
     * logstore object
     *
     * @var \Class logstore
     */
    protected $store;

    /**
     * Process constructor.
     *
     * @param array $events event ids
     * @param bool $failed this events are failed
     * @throws \invalid_parameter_exception
     */
    public function __construct(array $events, $failed = true) {
        global $DB;

        $this->eventids = $events;
        $this->table = self::LOGSTORE_FAILED;

        if (!$failed) {
            $this->table = self::LOGSTORE_NEW;
        }

        if (!empty($events)) {
            list($insql, $this->params) = $DB->get_in_or_equal($this->eventids);
            $this->select = 'id ' . $insql;
        }

        if (!$this->checkids()) {
            throw new \invalid_parameter_exception("Unrecognised event's id given.");
        }

        $manager = get_log_manager();
        $this->store = new store($manager);
    }

    /**
     * Double check event ids are valid in the table.
     *
     * @return bool
     */
    protected function checkids() {
        global $DB;

        if (empty($this->eventids)) {
            return true;
        }

        $counted = count($this->eventids);

        $valid = $DB->count_records_select($this->table, $this->select, $this->params);

        if ($valid === $counted) {
            return true;
        }
        return false;
    }

    /**
     * Return events limited by limitnum.
     *
     * @param int $limitnum if 0, means need all.
     * @return array
     */
    protected function extract_events($limitnum = 0) {
        global $DB;

        $events = $DB->get_records_select($this->table, $this->select, $this->params, '', '*', 0, $limitnum);

        return $events;
    }

    /**
     * Get events IDs from the array of event objects.
     * @param array $events Events
     * @return array Events' IDs
     */
    protected function get_event_ids(array $events) {
        return array_map(function ($event) {
            return $event['event']->id;
        }, $events);
    }

    /**
     * Get failed events from loaded events array.
     *
     * @param array $events Loaded events
     * @return array Failed events
     */
    public function get_failed_events(array $events) {
        $nonloadedevents = array_filter($events, function ($loadedevent) {
            return $loadedevent['loaded'] === false;
        });
        $failedevents = array_map(function ($nonloadedevent) {
            return $nonloadedevent['event'];
        }, $nonloadedevents);
        return $failedevents;
    }

    /**
     * Get successful events from loaded events array.
     *
     * @param array $events Loaded events
     * @return array Successful events
     */
    public function get_successful_events(array $events) {
        $loadedevents = array_filter($events, function ($loadedevent) {
            return $loadedevent['loaded'] === true;
        });
        $successfulevents = array_map(function ($loadedevent) {
            return $loadedevent['event'];
        }, $loadedevents);
        return $successfulevents;
    }

    /**
     * Remove failed events and store them.
     *
     * @param array $events
     */
    public function store_failed_events(array $events) {
        global $DB;

        $eventids = $this->get_event_ids($events);

        $DB->delete_records_list($this->table, 'id', $eventids);

        $DB->insert_records(self::LOGSTORE_FAILED, $events);

        mtrace(count($events) . " " . get_string('failed_events', 'logstore_xapi'));
    }

    /**
     * Record successfil events.
     *
     * @param $events
     */
    public function record_successful_events($events) {
        mtrace(count($events) . " " . get_string('successful_events', 'logstore_xapi'));
    }

    /**
     * Remove processed events
     *
     * @param $events
     * @return void
     */
    public function delete_processed_events($events) {
        global $DB;

        $eventids = $this->get_event_ids($events);

        $DB->delete_records_list($this->table, 'id', $eventids);
    }

    /**
     * Do the job.
     * Throw exceptions on errors (the job will be retried).
     */
    public function execute() {
        $events = $this->extract_events($this->store->get_max_batch_size());

        if (empty($events)) {
            return;
        }

        $loadedevents = $this->store->process_events($events);
        $failedevents = $this->get_failed_events($loadedevents);
        $successfulevents = $this->get_successful_events($loadedevents);
        $this->store_failed_events($failedevents);
        $this->record_successful_events($successfulevents);
        $this->delete_processed_events($successfulevents);
    }
}