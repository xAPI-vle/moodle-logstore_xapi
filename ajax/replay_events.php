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
 * Call process events to replay those.
 *
 * @package     logstore_xapi
 * @author      Záborski László <laszlo.zaborski@learningpool.com>
 * @copyright   2020 Learning Pool Ltd (http://learningpool.com)
 */

define('AJAX_SCRIPT', true);

require_once(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))) . '/config.php');

ini_set('display_errors', '0');
$CFG->debug = DEBUG_NONE;

require_login();

require_sesskey();

require_capability('tool/logstorexapi:manageerrors', context_system::instance());

$eventids = optional_param_array('events', 0, PARAM_INT);

$process = new \logstore_xapi\log\process($eventids);
$process->execute();

$return = array(
    'success' => 1,
    'processed' => count($process->successfulevents),
    'failed' => count($process->failedevents)
);

echo json_encode($return);