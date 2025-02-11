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
 * Transformer utilities for creating CMI matching xAPI Activity object definitions.
 *
 * @package   logstore_xapi
 * @copyright Milt Reder <milt@yetanalytics.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity\definition\cmi;

use src\transformer\utils as utils;

/**
 * Transformer util for creating matching definitions
 *
 * @param array $config The transformer config settings.
 * @param string $name The activity name.
 * @param ?string $description The activity description.
 * @param array $source The source values.
 * @param array $target The target values, ordered as the source values.
 * @param string $lang The language.
 */
function matching(
    array $config,
    string $name,
        ?string $description,
    array $source,
    array $target,
    string $lang
) {
    $sourceitems = array_map(
        function($answer) use ($lang) {
            return [
                'id' => utils\slugify($answer),
                'description' => [
                    $lang => $answer,
                ],
            ];
        },
        $source
    );
    $targetitems = array_map(
        function($answer) use ($lang) {
            return [
                'id' => utils\slugify($answer),
                'description' => [
                    $lang => $answer,
                ],
            ];
        },
        $target
    );

    return [
        ...common($config, $name, $description, $lang),
        'interactionType' => 'matching',
        'correctResponsesPattern' => [
            implode(
                '[,]',
                array_map(
                    function($src, $tgt) {
                        return $src['id'] . '[.]' . $tgt['id'];
                    },
                    $sourceitems,
                    $targetitems
                )
            ),
        ],
        'source' => $sourceitems,
        'target' => $targetitems,
    ];
}
