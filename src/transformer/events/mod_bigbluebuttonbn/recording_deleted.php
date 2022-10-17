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
 * The mod_bigbluebuttonbn recording deleted event.
 *
 * @package     logstore_xapi
 * @copyright   Paul Walter (https://github.com/paulito-bandito)
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\events\mod_bigbluebuttonbn;

/**
 * Create the statement from available data in Moodle and BigBlueButton.
 *
 * @param array $config
 * @param \stdClass $event
 * @param $evtid                The URL of the Verb we wish to use.
 *                                  (example: 'http://activitystrea.ms/schema/1.0/leave' )
 * @param $evtdispname         The conjugated Verb so it reads better in SCORM log.
 *                                  (example: 'left' )
 * @return array
 */
use function src\transformer\events\mod_bigbluebuttonbn\create_stmt;

/**
 * Transformer for bigbluebutton recording deleted event.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $event The event to be transformed.
 * @return array
 */
function recording_deleted(array $config, \stdClass $event) {
    return create_stmt( $config, $event, 'https://w3id.org/xapi/dod-isd/verbs/deleted', 'deleted' );
}
