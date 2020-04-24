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

require_once($CFG->dirroot.'/admin/tool/log/store/xapi/lib.php');
require_once($CFG->libdir.'/weblib.php');
require_once($CFG->libdir.'/classes/user.php');
require_once($CFG->libdir.'/messagelib.php');

use tool_log\log\manager;
use logstore_xapi\log\store;

class sendfailednotifications_task extends \core\task\scheduled_task {

    /**
     * Constants
     * Repurpose email_to_user() to send for users with just email addresses.
     */
    const DEFAULT_RECEIVER = -99;
    const DEFAULT_RECEIVER_NAME = "";
    const DEFAULT_SENDER = -99;
    const DEFAULT_SENDER_NAME = "";
    const DEFAULT_SENDER_EMAIL = "";

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('tasksendfailednotifications', 'logstore_xapi');
    }

    /**
     * Get failed rows.
     *
     * @return array
     */
    private function get_failed_rows() {
        global $DB;

        $sql = "SELECT x.id, x.errortype AS type, x.eventname, u.firstname, u.lastname, x.contextid, x.response, x.timecreated
                  FROM {logstore_xapi_failed_log} x
             LEFT JOIN {user} u ON u.id = x.userid";
    
        $results = $DB->get_records_sql($sql);
        return $results;
    }

    /**
     * Get failed email message.
     *
     * @return string email message in html
     */
    private function get_failed_email_message($results) {
        $emailmsg = "";

        // styles
        $emailmsg .= '<style type="text/css">.header {text-align:left;}</style>';
    
        // first line
        $emailmsg .= \html_writer::tag('p', get_string('failedtosend', 'logstore_xapi'));
    
        // summary info
        $endpointname = get_string('endpoint', 'logstore_xapi');
        $url = get_config('logstore_xapi', 'endpoint');
        $endpointurl = \html_writer::tag('a', $url, array('target' => '_blank', 'href' => $url));
    
        $errorlogpage = get_string('errorlogpage', 'logstore_xapi');
        $url = new \moodle_url("/admin/tool/log/store/xapi/report.php");
        $errorlogurl = \html_writer::tag('a', $url, array('target' => '_blank', 'href' => $url));
    
        // first table
        $table = new \html_table();
    
        // data
        $table->data[] = array($endpointname, $endpointurl);
        $table->data[] = array($errorlogpage, $errorlogurl);
    
        // add table to message
        $emailmsg .= \html_writer::table($table);
    
        // separator
        $emailmsg .= \html_writer::tag('h3', get_string('failurelog', 'logstore_xapi'));
    
        // second table
        $table = new \html_table();

        // header
        $heading1 = get_string('datetimegmt', 'logstore_xapi');
        $heading2 = get_string('eventname', 'logstore_xapi');
        $heading3 = get_string('response', 'logstore_xapi');
        $table->head = array($heading1, $heading2, $heading3);
    
        // data
        foreach ($results as $result) {
            $col1 = userdate($result->timecreated);
            $col2 = $result->eventname;
            $col3 = $result->response;
            $table->data[] = array($col1, $col2, $col3);
        }
    
        // add table to message
        $emailmsg .= \html_writer::table($table);
    
        return $emailmsg;
    }

    /**
     * Send email using email_to_user.
     *
     * @return int 1 = sent, 0 = not sent
     */
    private function sendmail($msg, $subject, $emailto) {

        $user = new \stdClass();
        $user->id = self::DEFAULT_RECEIVER;
        $user->username = self::DEFAULT_RECEIVER_NAME;
        $user->email = $emailto;
        $user->deleted = 0;
        $user->mailformat = FORMAT_HTML;
    
        $from = new \stdClass();
        $from->id = self::DEFAULT_SENDER;
        $from->username = self::DEFAULT_SENDER_NAME;
        $from->email = self::DEFAULT_SENDER_EMAIL;
        $from->deleted = 0;
        $from->mailformat = FORMAT_HTML;
    
        $messageid = email_to_user($user, $from, $subject, html_to_text($msg), $msg);
        return $messageid;
    }

    /**
     * Do the job.
     * Throw exceptions on errors (the job will be retried).
     */
    public function execute() {
        $manager = get_log_manager();
        $store = new store($manager);

        echo "In send failed notifications task execute".PHP_EOL;

        $results = $this->get_failed_rows();
        if (count($results) == 0) {
            echo get_string('norows', 'logstore_xapi').PHP_EOL;
            return;
        }

        $subject = get_string('failedsubject', 'logstore_xapi');
        $msg = $this->get_failed_email_message($results);

        $users = logstore_xapi_distinct_email_addresses();
        foreach ($users as $user) {
            $this->sendmail($msg, $subject, $user);
        }
    }
}
