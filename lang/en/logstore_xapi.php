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

defined('MOODLE_INTERNAL') || die();

$string['endpoint'] = 'Your LRS endpoint for the xAPI';
$string['settings'] = 'General Settings';
$string['xapifieldset'] = 'Custom example fieldset';
$string['xapi'] = 'xAPI';
$string['password'] = 'Your LRS basic auth secret/password for the xAPI';
$string['pluginadministration'] = 'Logstore xAPI administration';
$string['pluginname'] = 'Logstore xAPI';
$string['submit'] = 'Submit';
$string['username'] = 'Your LRS basic auth key/username for the xAPI';
$string['xapisettingstitle'] = 'Logstore xAPI Settings';
$string['backgroundmode'] = 'Send statements by scheduled task?';
$string['backgroundmode_desc'] = 'This will force Moodle to send the statements to the LRS in the background,
        via a cron task to avoid blocking page responses. This will make the process less close to real time, but will help to prevent unpredictable
        Moodle performance linked to the performance of the LRS.';
$string['maxbatchsize'] = 'Maximum batch size';
$string['maxbatchsize_desc'] = 'Statements are sent to the LRS in batches. This setting controls the maximum number of
        statements that will be sent in a single operation. Setting this to zero will cause all available statements to
        be sent at once, although this is not recommended.';
$string['maxbatchsizeforfailed'] = 'Maximum batch size for failed requests';
$string['maxbatchsizeforfailed_desc'] = 'Statements are sent to the LRS in batches. This setting controls the maximum number of
        statements that will be sent in a single operation for failed requests. Setting this to zero will cause all available statements to
        be sent at once, although this is not recommended.';
$string['maxbatchsizeforhistorical'] = 'Maximum batch size for historical requests';
$string['maxbatchsizeforhistorical_desc'] = 'Statements are sent to the LRS in batches. This setting controls the maximum number of
        statements that will be sent in a single operation for historical requests. Setting this to zero will cause all available statements to
        be sent at once, although this is not recommended.';
$string['maxbatchsizeforreconciled'] = 'Maximum batch size for reconciled requests';
$string['maxbatchsizeforreconciled_desc'] = 'Statements are sent to the LRS in batches. This setting controls the maximum number of
        statements that will be sent in a single operation for reconciled requests. Setting this to zero will cause all available statements to
        be sent at once, although this is not recommended.';
$string['taskemit'] = 'Emit records to LRS';
$string['taskfailed'] = 'Emit failed records to LRS';
$string['taskhistorical'] = 'Emit historical records to LRS';
$string['taskreconciled'] = 'Emit reconciled records to LRS';
$string['enablesendingnotifications'] = 'Send notifications?';
$string['enablesendingnotifications_desc'] = 'Control if notifications should be sent to configured recipients.';
$string['errornotificationtrigger'] = 'Error notification trigger';
$string['errornotificationtrigger_desc'] = 'Threshold value at which point notifications will be triggered. When a number of errors greater than this value have been generated, the notification is sent.';
$string['cohorts'] = 'Cohorts';
$string['cohorts_help'] = 'Add cohort(s) to notifications';
$string['includecohorts'] = 'Include these cohorts in notifications';
$string['send_additional_email_addresses'] = 'Additional email addresses';
$string['send_additional_email_addresses_desc'] = 'Send notifications to list of email addresses. Comma separated values.';
$string['routes'] = 'Include actions with these routes';
$string['failed_events'] = 'event(s) have failed to send to the LRS.';
$string['successful_events'] = 'event(s) have been successfully processed.';
$string['filters'] = 'Filter logs';
$string['logguests'] = 'Log guest actions';
$string['filters_help'] = 'Enable filters that INCLUDE some actions to be logged.';
$string['mbox'] = 'Identify users by email';
$string['mbox_desc'] = 'Statements will identify users with their email (mbox) when this box is ticked.';
$string['send_username'] = 'Identify users by id';
$string['send_username_desc'] = 'Statements will identify users with their username when this box is ticked, but only if identifying users by email is disabled.';
$string['send_jisc_data'] = 'Adds JISC data to statements';
$string['send_jisc_data_desc'] = 'Statements will contain data required by JISC.';
$string['shortcourseid'] = 'Send short course name';
$string['shortcourseid_desc'] = 'Statements will contain the shortname for a course as a short course id extension';
$string['sendidnumber'] = 'Send course and activity ID number';
$string['sendidnumber_desc'] = 'Statements will include the ID number (admin defined) for courses and activities in the object extensions';
$string['send_response_choices'] = 'Send response choices';
$string['send_response_choices_desc'] = 'Statements for multiple choice and sequencing question answers will be sent to the LRS with the correct response and potential choices';
$string['resendfailedbatches'] = 'Resend failed batches';
$string['resendfailedbatches_desc'] = 'When processing events in batches, try re-sending events in smaller batches if a batch fails. If not selected, the whole batch will not be sent in the event of a failed event.';
$string['type'] = 'Type';
$string['eventname'] = 'Event Name';
$string['username'] = 'Username';
$string['eventcontext'] = 'Event Context';
$string['response'] = 'Response';
$string['errortype'] = 'Error Type';
$string['info'] = 'Info';
$string['datetimegmt'] = 'Date/Time (GMT)';
$string['logstorexapierrorlog'] = 'Logstore xAPI Error Log';
$string['noerrorsfound'] = 'No errors found';
$string['logstorexapi:viewerrorlog'] = 'View xAPI error log';
$string['datetovalidation'] = 'The To date cannot be before the From date';
$string['failedtransformerrortype'] = '101';
$string['failedtransformresponse'] = 'Not transformed successfully';
$string['logstorexapi:manageerrors'] = 'Replay failed statements';
$string['privacy:metadata:logstore_xapi_log'] = 'xAPI holding table for cron processing';
$string['privacy:metadata:logstore_xapi_log:userid'] = 'User Id of xAPI holding table for cron processing';
$string['privacy:metadata:logstore_xapi_failed_log'] = 'xAPI holding table for failed events';
$string['privacy:metadata:logstore_xapi_failed_log:userid'] = 'User Id of xAPI holding table for failed events';

// Info strings from xAPI errors
$string['networkerror'] = 'There was a network error sending the response: {$a}';
$string['recipeerror'] = 'The LRS responded with a 400 error, this can be due to an issue with the recipe. The following message
    was returned: "{$a}"';
$string['autherror'] = 'The server is returning a 401 error: {$a}. Please ensure the endpoint, username and auth secret/password for
    the xAPI is correct in the Logstore xAPI settings.';
$string['unknownerror'] = 'Error code: "{$a->errortype}" Response: "{$a->response}"';
$string['lrserror'] = 'There is a problem with the LRS. The LRS has responded with a 500 error. Response: {$a}';
