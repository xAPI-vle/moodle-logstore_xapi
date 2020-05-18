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

use core\output\notification;

require_once(__DIR__ . '/../../../../../config.php');
require_once($CFG->dirroot . '/lib/adminlib.php');
require_once($CFG->dirroot . '/admin/tool/log/store/xapi/lib.php');
require_once($CFG->dirroot . '/admin/tool/log/store/xapi/classes/form/reportfilter_form.php');

define('XAPI_REPORT_STARTING_PAGE', 0);
define('XAPI_REPORT_PERPAGE_DEFAULT', 30);
define('XAPI_REPORT_RESEND_FALSE', false);
define('XAPI_REPORT_RESEND_TRUE', true);

// Set context.
$systemcontext = context_system::instance();

$id           = optional_param('id', XAPI_REPORT_ID_ERROR, PARAM_INT); // This is the report ID
$page         = optional_param('page',XAPI_REPORT_STARTING_PAGE, PARAM_INT);
$perpage      = optional_param('perpage', XAPI_REPORT_PERPAGE_DEFAULT, PARAM_INT);

$baseurl = new moodle_url('/admin/tool/log/store/xapi/report.php', array('id' => $id, 'page' => $page, 'perpage' => $perpage));

// Set page parameters.
$PAGE->set_context($systemcontext);
$PAGE->set_url($baseurl);

$canmanageerrors = has_capability('tool/logstorexapi:manageerrors', context_system::instance());

$eventnames = logstore_xapi_get_event_names_array();

$filterparams = [
    'reportid' => $id,
    'eventnames' => $eventnames,
    'resend' => XAPI_REPORT_RESEND_FALSE
];

$basetable = '{logstore_xapi_failed_log}';
$extraselect = 'x.errortype, x.response';
$filterparams['errortypes'] = logstore_xapi_get_distinct_options_from_failed_table('errortype');
$filterparams['responses'] = logstore_xapi_get_distinct_options_from_failed_table('response');
$pagename = 'logstorexapierrorlog';

if ($id == XAPI_REPORT_ID_HISTORIC) {
    $basetable = '{logstore_standard_log}';
    $extraselect = 'u.username, x.contextid';
    $filterparams['eventcontexts'] = logstore_xapi_get_logstore_standard_context_options();
    $pagename = 'logstorexapihistoriclog';
}


$notifications = array();
$mform = new tool_logstore_xapi_reportfilter_form($baseurl, $filterparams, 'get');

$params = [];
$where = ['1 = 1'];

if ($fromform = $mform->get_data()) {
    if (!empty($fromform->userfullname)) {
        $userfullname = $DB->sql_fullname('u.firstname', 'u.lastname');
        $where[] = $DB->sql_like($userfullname, ':userfullname', false, false);
        $params['userfullname'] = '%' . $fromform->userfullname . '%';
    }

    if (!empty($fromform->errortype)) {
        $where[] = 'x.errortype = :errortype';
        $params['errortype'] = $fromform->errortype;
    }

    if (!empty($fromform->eventcontext)) {
        $where[] = 'x.contextid = :eventcontext';
        $params['eventcontext'] = $fromform->eventcontext;
    }

    if (!empty($fromform->eventnames)) {
        $eventnames = $fromform->eventnames;
    }

    if (!empty($fromform->response)) {
        $where[] = 'x.response = :response';
        $params['response'] = $fromform->response;
    }

    if (!empty($fromform->datefrom)) {
        $where[] = 'x.timecreated >= :datefrom';
        $params['datefrom'] = $fromform->datefrom;
    }

    if (!empty($fromform->dateto)) {
        $where[] = 'x.timecreated <= :dateto';
        $params['dateto'] = $fromform->dateto;
    }

    // Last investigated element.
    $canresenderrors = !empty($fromform->resend) && $fromform->resend == XAPI_REPORT_RESEND_TRUE && $canmanageerrors;

    if ($canresenderrors) {
        $wheremove = implode(' AND ', $where);

        $sql = "SELECT x.id
                  FROM {$basetable} x
             LEFT JOIN {user} u
                    ON u.id = x.userid
                 WHERE $wheremove";
        $eventids = array_keys($DB->get_records_sql($sql, $params));

        if (!empty($eventids)) {
            $mover = new \logstore_xapi\log\moveback($eventids);
            if ($mover->execute()) {
                $notifications[] = new notification(get_string('resendevents:success', 'logstore_xapi'), notification::NOTIFY_SUCCESS);
            } else {
                $notifications[] = new notification(get_string('resendevents:failed', 'logstore_xapi'), notification::NOTIFY_ERROR);
            }
        }
    }
}

list($insql, $inparams) = $DB->get_in_or_equal($eventnames, SQL_PARAMS_NAMED, 'evt');
$where[] = "x.eventname $insql";
$params = array_merge($params, $inparams);

$where = implode(' AND ', $where);

$sql = "SELECT x.id, x.eventname, u.firstname, u.lastname, x.contextid, x.timecreated, $extraselect
          FROM {$basetable} x
     LEFT JOIN {user} u
            ON u.id = x.userid
         WHERE $where";
$results = $DB->get_records_sql($sql, $params, $page*$perpage, $perpage);

$sql = "SELECT COUNT(x.id)
          FROM {$basetable} x
     LEFT JOIN {user} u
            ON u.id = x.userid
         WHERE $where";
$count = $DB->count_records_sql($sql, $params);

// Now we have the count we can set this value for the submit button
$submitcount = new stdClass();
$submitcount->resendselected = get_string('resendevents', 'logstore_xapi', ['count' => $count]);
$mform->set_data($submitcount);

if (!empty($results)) {
    $table = new html_table();
    $table->head = array();
    $table->attributes['class'] = 'admintable generaltable';
    if ($id == XAPI_REPORT_ID_ERROR) {
        $table->head[] = get_string('type', 'logstore_xapi');
    }
    $table->head[] = get_string('eventname', 'logstore_xapi');
    if ($id == XAPI_REPORT_ID_HISTORIC) {
        $table->head[] = get_string('username', 'logstore_xapi');
        $table->head[] = get_string('eventcontext', 'logstore_xapi');
    }
    if ($id == XAPI_REPORT_ID_ERROR) {
        $table->head[] = get_string('response', 'logstore_xapi');
    }
    $table->head[] = get_string('info', 'logstore_xapi');
    $table->head[] = get_string('datetimegmt', 'logstore_xapi');
    $table->head[] = '';
    $table->id = "report";

    foreach ($results as $result) {
        $row = [];
        if ($id == XAPI_REPORT_ID_ERROR) {
            $row[] = $result->errortype;
        }
        $row[] = $result->eventname;
        if ($id == XAPI_REPORT_ID_HISTORIC) {
            $row[] = $result->username;
            $context = context::instance_by_id($result->contextid);
            $row[] = $context->get_context_name();
        }
        if ($id == XAPI_REPORT_ID_ERROR) {
            $response = '';
            if (isset($result->response)) {
                $response = '<pre>' . print_r(logstore_xapi_decode_response($result->response), true) . '</pre>';
            }
            $row[] = $response;
        }
        $row[] = logstore_xapi_get_info_string($result);
        $row[] = userdate($result->timecreated);

        // Add container to the individual reply statements.
        $replycontainer = \html_writer::start_span('reply-event', ['id' => 'reply-event-id-' . $result->id]);
        $replycontainer .= \html_writer::end_span();
        $row[] = $replycontainer;

        $table->data[] = $row;
    }
}

// Define the page layout and header/breadcrumb.
$PAGE->set_pagelayout('report');
$PAGE->set_title(get_string($pagename, 'logstore_xapi'));
$PAGE->set_heading(get_string($pagename, 'logstore_xapi'));
$PAGE->navbar->add(get_string('administrationsite'), new moodle_url('/admin/search.php'), navigation_node::TYPE_CUSTOM, null, 'dashboard');
$PAGE->navbar->add(get_string('plugins', 'admin'), new moodle_url('/admin/category.php', ['category' => 'modules']));
$PAGE->navbar->add(get_string('logging', 'tool_log'), new moodle_url('/admin/category.php', ['category' => 'logging']));
$PAGE->navbar->add($PAGE->heading, $baseurl);

// Add requested items to the page view.
if ($canmanageerrors) {
    $PAGE->requires->js_call_amd('logstore_xapi/replayevents', 'init', [$count]);
}
$PAGE->requires->css('/admin/tool/log/store/xapi/styles.css');

// Show page.
echo $OUTPUT->header();

if (!empty($notifications)) {
    foreach ($notifications as $notification) {
        echo $OUTPUT->render($notification);
    }
}

echo \html_writer::start_div('', ['id' => 'xapierrorlog']);
echo \html_writer::start_div('', ['id' => 'xapierrorlog_form']);
$mform->display();
echo \html_writer::end_div();

if (empty($results)) {
    echo $OUTPUT->heading(get_string('noerrorsfound', 'logstore_xapi'));
} else {
    echo \html_writer::start_div('no-overflow', ['id' => 'xapierrorlog_data']);
    echo \html_writer::table($table);
    echo \html_writer::end_div();
    echo $OUTPUT->paging_bar($count, $page, $perpage, $baseurl);
}
echo \html_writer::end_div();
echo $OUTPUT->footer();
