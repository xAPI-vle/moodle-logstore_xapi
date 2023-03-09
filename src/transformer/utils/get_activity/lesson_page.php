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
 * Transformer utility for retrieving (lesson content page) activities.
 *
 * @package   logstore_xapi
 * @copyright 2023 Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity;

/**
 * Transformer utility for retrieving (lesson content page) activities.
 *
 * @param array $config The transformer config settings.
 * @param string $lang The language of the badge.
 * @param int $cmid The course module id.
 * @param \stdClass $page The object page.
 * @param string $target The type of content page.
 * @return array
 */
function lesson_page(array $config, string $lang, int $cmid, \stdClass $page, string $target): array {

    if ($target == 'question') {
        $pagename = 'Question: ' . property_exists($page, 'title') ? $page->title : 'Question';
        $type = 'http://adlnet.gov/expapi/activities/question';
    } else {
        $pagename = 'Content page: ' . property_exists($page, 'title') ? $page->title : 'Content page';
        $type = 'http://activitystrea.ms/schema/1.0/page';
    }

    $pageurl = $config['app_url'] . '/mod/lesson/view.php?id=' . $cmid . '&pageid=' . $page->id;

    return [
        'id' => $pageurl,
        'definition' => [
            'type' => $type,
            'name' => [
                $lang => $pagename,
            ],
        ],
    ];
}


