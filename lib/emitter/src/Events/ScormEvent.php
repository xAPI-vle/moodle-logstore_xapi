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

namespace XREmitter\Events;

defined('MOODLE_INTERNAL') || die();

class ScormEvent extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return array_merge(parent::read($opts), [
            'object' => $this->read_module($opts),
            'context' => [
                'contextActivities' => [
                    'grouping' => [
                        $this->read_course($opts),
                        $this->read_scorm_scoes($opts),
                    ],
                ],
            ],
        ]);
    }

    protected function read_scorm_verb($opts) {
        $scormstatus = $opts['scorm_status'];
        $verbbaseurl = 'http://adlnet.gov/expapi/verbs/';
        $verb = array();

        switch ($scormstatus) {
            case 'failed':
                $verburl = $verbbaseurl . $scormstatus;
                $verb = $scormstatus;
                break;
            case 'passed':
                $verburl = $verbbaseurl . $scormstatus;
                $verb = $scormstatus;
                break;
            default:
                $verburl = $verbbaseurl . 'completed';
                $verb = 'completed';
        }

        static::$verbdisplay = ['en' => $verb];

        $lang = [
            'id' => $verburl,
            'display' => $this->read_verb_display($opts),
        ];

        return $lang;
    }

    protected function read_scorm_scoes($opts) {
        return [
            'id' => $opts['module_url'],
            'definition' => [
                'type' => $opts['scorm_scoes_type'],
                'name' => [
                    $opts['context_lang'] => $opts['scorm_scoes_name'],
                ],
                'description' => [
                    $opts['context_lang'] => $opts['scorm_scoes_description'],
                ],
            ],
        ];
    }
}

