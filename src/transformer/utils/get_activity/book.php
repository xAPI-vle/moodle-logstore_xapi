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
 * Transformer utility for retrieving (book) activities.
 *
 * @package   logstore_xapi
 * @copyright 2023 Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

namespace src\transformer\utils\get_activity;


/**
 * Transformer utility for retrieving the book.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $book The book object.
 * @param int $bookid The id of the book.
 * @param string $lang The language of the book.
 * @return array
 */
function book(array $config, \stdClass $book, int $bookid, string $lang): array {

    $bookurl = $config['app_url'].'/mod/book/tool/print/index.php?id=' . $bookid;
    $bookname = property_exists($book, 'name') ? $book->name : 'Book';

    return [
        'id' => $bookurl,
        'definition' => [
            'type' => 'http://id.tincanapi.com/activitytype/book',
            'name' => [
                $lang => $bookname,
            ],
        ],
    ];
}
