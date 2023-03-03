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
 * Transformer utility for retrieving (choice) activities.
 *
 * @package   logstore_xapi
 * @copyright 2023 Daniela Rotelli <danielle.rotelli@gmail.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\utils\get_activity;

/**
 * Transformer utility for retrieving (choice) activities.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $choice The choice object.
 * @param int $cmid The course module id.
 * @param \stdClass $choice_answers
 * @param \stdClass $choice_options
 * @param string $lang
 * @return array
 */

//function choice(array $config, \stdClass $choice, int $cmid, \stdClass $choice_answers, \stdClass $choice_options, string $lang): array {
function choice(array $config, \stdClass $choice, int $cmid, string $lang): array {
    /**
    $repo = $config['repo'];
    $options = [];
    foreach ($choice_options as $co) {
    $option = $repo->read_record_by_id('choice_options', $co->id);
    $options[] = [
        'id' => $co->id,
        $lang => $co->text,
        ];
    }

    $answers = [];
    foreach ($choice_answers as $ca) {
        $answer = $repo->read_record_by_id('choice_options', $ca->optionid);
        $answers[] = $answer->text;
    }

    $choiceurl = $config['app_url'] . '/mod/choice/view.php?id=' . $cmid ;
    $choicename = property_exists($choice, 'name') ? $choice->name : 'Choice';

    return [
        'id' => $choiceurl,
        'definition' => [
            'description' => [
                $lang => $choice->intro,
            ],
            'type' => 'http://adlnet.gov/expapi/activities/cmi.interaction',
            //'interactionType' => 'choice',
            //'correctResponsesPattern' => implode ('[,]', $answers),
            //'choices' => $options,
            'name' => [
                $lang => $choicename,
            ],
        ],
    ];
    */

    $choiceurl = $config['app_url'] . '/mod/choice/view.php?id=' . $cmid ;
    $choicename = property_exists($choice, 'name') ? $choice->name : 'Choice';

    return [
        'id' => $choiceurl,
        'definition' => [
            'type' => 'http://adlnet.gov/expapi/activities/cmi.interaction',
            'name' => [
                $lang => $choicename,
            ],
        ],
    ];

}

