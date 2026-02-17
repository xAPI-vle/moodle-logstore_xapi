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
 * Transformer utility for retrieving SCORM tracks from Moodle 4.3+ tables.
 *
 * @package   logstore_xapi
 * @copyright David Pesce <david.pesce@exputo.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils;

/**
 * Retrieves SCORM track data from the normalized Moodle 4.3+ tables.
 *
 * Returns objects with 'element' and 'value' properties, matching the shape
 * expected by get_scorm_verb() and get_scorm_result().
 *
 * @param array $config The transformer config settings.
 * @param int $userid The user ID.
 * @param int $scormid The SCORM activity ID.
 * @param int $scoid The SCO ID.
 * @param int $attempt The attempt number.
 * @return array
 */
function get_scorm_tracks(array $config, int $userid, int $scormid, int $scoid, int $attempt) {
    $repo = $config['repo'];

    $attemptrecord = $repo->read_record('scorm_attempt', [
        'userid' => $userid,
        'scormid' => $scormid,
        'attempt' => $attempt,
    ]);

    $values = $repo->read_records('scorm_scoes_value', [
        'attemptid' => $attemptrecord->id,
        'scoid' => $scoid,
    ]);

    $tracks = [];
    foreach ($values as $v) {
        $element = $repo->read_record_by_id('scorm_element', $v->elementid);
        $track = new \stdClass();
        $track->element = $element->element;
        $track->value = $v->value;
        $tracks[] = $track;
    }

    return $tracks;
}
