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
 * Transformer utility for retrieving (chapter) activities.
 *
 * @package   logstore_xapi
 * @copyright Jerret Fowler <jerrett.fowler@gmail.com>
 *            Ryan Smith <https://www.linkedin.com/in/ryan-smith-uk/>
 *            David Pesce <david.pesce@exputo.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity;

use src\transformer\utils as utils;

/**
 * Transformer utility for retrieving the chapter.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $course The course object.
 * @param \stdClass $chapter The chapter object.
 * @param string $cmid The id of the context.
 * @return array
 */
function chapter(array $config, \stdClass $chapter, int $moduleid, string $lang) {

    $chapterurl = $config['app_url'] . '/mod/book/tool/print/index.php?id=' . $moduleid . '&chapterid=' . $chapter->id;
    $chaptertitle = property_exists($chapter, 'title') ? $chapter->title : 'Chapter';

    return [
        'id' => $chapterurl,
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/chapter',
            'name' => [
                $lang => $chaptertitle,
            ],
        ],
    ];
}