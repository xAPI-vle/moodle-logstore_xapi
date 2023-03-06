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
 * Transformer utility for retrieving (folder) activities.
 *
 * @package   logstore_xapi
 * @copyright 2023 Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity;

/**
 * Transformer utility for retrieving (folder) activities.
 *
 * @param array $config The transformer config settings.
 * @param string $lang The language of the group.
 * @param \stdClass $folder The folder object.
 * @return array
 */

function folder(array $config, string $lang, \stdClass $folder, int $cmid): array {

    $folderurl = $config['app_url'] . '/mod/folder/view.php?id=' . $cmid;
    $foldername = property_exists($folder, 'name') ? $folder->name : 'Folder';

    return [
        'id' => $folderurl,
        'definition' => [
            'type' => 'http://activitystrea.ms/schema/1.0/group',
            'name' => [
                $lang => $foldername,
            ],
        ],
    ];
}
