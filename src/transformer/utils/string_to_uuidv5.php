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
 * Utility to form UUID hashes of strings
 *
 * @package   logstore_xapi
 * @copyright Milt Reder <milt@yetanalytics.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils;

/**
 * Transformer utility to hash strings as UUIDv5
 *
 * @param string $str The string to hash.
 * @return string
 */
function stringtouuidv5($str) {
    $namespace = '12345678-1234-1234-1234-123456789abc';
    // Generate SHA-1 hash of the namespace concatenated with the input string.
    $hash = sha1($namespace . $str);

    // Properly format the hash as a UUID v5.
    $timelow = substr($hash, 0, 8);
    $timemid = substr($hash, 8, 4);
    $timehiandversion = substr($hash, 12, 4);
    $clockseqhiandreserved = substr($hash, 16, 4);
    $node = substr($hash, 20, 12);

    // Overwrite version to 5 and adjust the variant.
    $timehiandversion = '5' . substr($timehiandversion, 1, 3);
    $clockseqhiandreserved = dechex((hexdec($clockseqhiandreserved) & 0x3f3f) | 0x8000);

    // Return the UUID.
    return sprintf('%s-%s-%s-%s-%s', $timelow, $timemid, $timehiandversion, $clockseqhiandreserved, $node);
}
