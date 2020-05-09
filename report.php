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

require_once(__DIR__ . '/../../../../../config.php');
require_once($CFG->dirroot . '/lib/adminlib.php');
require_once($CFG->dirroot . '/admin/tool/log/store/xapi/lib.php');
require_once($CFG->dirroot . '/admin/tool/log/store/xapi/classes/form/reportfilter_form.php');

define('XAPI_REPORT_STARTING_PAGE', 0);
define('XAPI_REPORT_PERPAGE_DEFAULT', 30);

$id           = optional_param('id', XAPI_REPORT_ID_ERROR, PARAM_INT); // This is the report ID
$page         = optional_param('page',XAPI_REPORT_STARTING_PAGE, PARAM_INT);
$perpage      = optional_param('perpage', XAPI_REPORT_PERPAGE_DEFAULT, PARAM_INT);

$baseurl = new moodle_url('/admin/tool/log/store/xapi/report.php', array('id' => $id, 'page' => $page, 'perpage' => $perpage));

navigation_node::override_active_url(new moodle_url('/admin/settings.php', array('section' => 'logstorexapierrorlog')));
admin_externalpage_setup('logstorexapierrorlog', '', null, $baseurl);

$eventnames = logstore_xapi_get_event_names_array();

$filterparams = [
    'reportid' => $id,
    'eventnames' => $eventnames
];

if ($id == XAPI_REPORT_ID_ERROR) {
    $filterparams['errortypes'] = logstore_xapi_get_distinct_options_from_failed_table('errortype');
    $filterparams['responses'] = logstore_xapi_get_distinct_options_from_failed_table('response');
} else if ($id == XAPI_REPORT_ID_HISTORIC) {
    $filterparams['eventcontexts'] = logstore_xapi_get_logstore_standard_context_options();
}

$mform = new tool_logstore_xapi_reportfilter_form($baseurl, $filterparams);

$params = [];
$where = ['1 = 1'];
$eventnamequery = false;

if ($fromform = $mform->get_data()) {
    if (!empty($fromform->fullname)) {
        $fullname = $DB->sql_fullname('u.firstname', 'u.lastname');
        $conditions[] = $DB->sql_like($fullname, ':fullname', false, false);
    }

    if (!empty($fromform->errortype)) {
        $where[] = 'x.errortype = :errortype';
        $params['errortype'] = $fromform->errortype;
    }

    if (!empty($fromform->eventname)) {
        $where[] = 'x.eventname = :eventname';
        $params['eventname'] = $fromform->eventname;
        // TODO: this is now a multi-select so we may need to add multiple params here.
        $eventnamequery = true;
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
}

if ($id == XAPI_REPORT_ID_ERROR) {
    $basetable = '{logstore_xapi_failed_log}';
    $extraselect = 'x.errortype, x.response';
} else {
    $basetable = '{logstore_standard_log}';
    $extraselect = 'u.username, x.contextid';
}

if ($eventnamequery == false) {
    list($insql, $inparams) = $DB->get_in_or_equal($eventnames, SQL_PARAMS_NAMED, 'evt');
    $where[] = "x.eventname $insql";
    $params = array_merge($params, $inparams);
}

$where = implode(' AND ', $where);
$sql = "SELECT x.id, x.eventname, u.firstname, u.lastname, x.contextid, x.timecreated, $extraselect
          FROM {$basetable} x
     LEFT JOIN {user} u
            ON u.id = x.userid
         WHERE $where";
$results = $DB->get_records_sql($sql, $params, $page*$perpage, $perpage);

$sql = "SELECT COUNT(id)
          FROM {$basetable} x
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

// Add requested items to the page view.
$PAGE->requires->js_call_amd('logstore_xapi/replayevents', 'init', [$count]);
$PAGE->requires->css('/admin/tool/log/store/xapi/styles.css');

// Show page.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('logstorexapierrorlog', 'logstore_xapi'));

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
