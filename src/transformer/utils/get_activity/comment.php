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
 * Transformer utility for retrieving (comment) activities.
 *
 * @package   logstore_xapi
 * @copyright 2023 Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity;

use Exception;
use src\transformer\utils as utils;
/**
 * Transformer utility for retrieving (comment) activities.
 *
 * @param array $config The transformer config settings.
 * @param string $lang The language of the group.
 * @param \stdClass $comment The comment object.
 * @return array
 */

function comment(array $config, string $lang, int $cmid): array {

    try {
        $repo = $config['repo'];
        $comment = $repo->read_record_by_id('comments', $cmid);
        $commentname = utils\get_string_html_removed(property_exists($comment, 'content')) ?
            utils\get_string_html_removed($comment->content) : 'Comment';

    } catch (Exception $e) {
        // OBJECT_NOT_FOUND.
        $commentname = 'comment id: ' . $cmid;
    }

    return [
        'id' =>  $config['app_url'],
        'definition' => [
            'type' => 'http://activitystrea.ms/schema/1.0/comment',
            'name' => [
                $lang => $commentname,
            ],
        ],
    ];
}
