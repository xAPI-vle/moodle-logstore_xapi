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
 * Transform for course viewed event.
 *
 * @package   logstore_xapi
 * @copyright Jerret Fowler <jerrett.fowler@gmail.com>
 *            Ryan Smith <https://www.linkedin.com/in/ryan-smith-uk/>
 *            David Pesce <david.pesce@exputo.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace src\transformer\events\core;

use src\transformer\utils as utils;

/**
 * Transformer for Badge Revoked Event.
 *
 * @param array $config The transformer config settings.
 * @param \stdClass $event The event to be transformed.
 * @return array
 */
function badge_revoked(array $config, \stdClass $event) {
    
    //debug
    $repo = $config['repo'];
    $event_object = $repo->read_record_by_id($event->objecttable, $event->objectid);
    $recipient =$repo->read_record_by_id('user', $event->relateduserid);
    $revoker = '?';

    
    $manual = $repo->read_record_by_id('badge_manual_award', $revokedid);
    
    $statement = [[
        'actor' => utils\get_user($config, $recipient),
        'verb' => [
            'id' => 'https://w3id.org/xapi/tla/verbs/forfeited',
            'display' => [
                'en' => 'Forfeited'
            ],
        ],
        'object' => [
            'id' => $config['app_url'].'/badges/overview.php?id='.$event->objectid,
            'objectType' => 'Activity',
            'definition' => [
                'name' => [$lang => $badge->name],
                'description' => [$lang => $badge->description],
                'type' => 'https://xapi.edlm/profiles/edlm-lms/concepts/activity-types/badge',
                'extensions' => [
                    'https://xapi.edlm/profiles/edlm-lms/v1/concepts/activity-extensions/badge-type' =>  $badgetype,
                    'https://xapi.edlm/profiles/edlm-lms/v1/concepts/activity-extensions/badge-version' => $badge->version 
                ]
            ]
        ],
        'context' => [
            'language'=>'?',
            'instructor' =>$revoker,
            'contextActivities'=> [
                'category' => [
                    'id' => $config['app_url'],
                    'objectType'  => 'Activity',
                    'definition'  => [
                        'name' => ['en'=> 'EDLM Moodle LMS'],
                        'type' => 'http://id.tincanapi.com/activitytype/lms'
                    ]
                ]
            ],
            'extensions' => array_merge(utils\extensions\base($config, $event, $course),[
                'https://xapi.edlm/profiles/edlm-lms/v1/concepts/context-extensions/badge-assignment-method' => ($manual ? 'Manual' : 'Automatic')])
        ]
    ]];

    $encoded =json_encode($statement);
    echo <<<END
             <script type="text/javascript">
             var s = $encoded;
             console.log(s);
             </script>
             END;
    return $statement;
}
