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
 * Apply global transformations to statements.
 *
 * @package   logstore_xapi
 * @copyright Milt Reder <milt@yetanalytics.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils;

use src\transformer\utils as utils;

/**
 * Given the config, source event and statements, apply global transformations.
 *
 * @param array $config configuration array.
 * @param \stdClass $event original event
 * @param array $statements generated xAPI statements.
 * @return array
 */
function apply_global_xforms(array $config, \stdClass $event, array $statements) {
    return array_map(function ($statement) use ($config, $event) {
        // apply any global transformations to statements.
        // Currently this is only adding registration
        return add_context_registration($config, $statement);
    }, $statements);
}
