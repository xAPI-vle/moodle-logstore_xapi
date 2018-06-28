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

namespace LogExpander\Events;

defined('MOODLE_INTERNAL') || die();

use \LogExpander\Repository as Repository;
use \stdClass as PhpObj;

class Event extends PhpObj {
    protected $repo;

    /**
     * Constructs a new Event.
     * @param repository $repo
     */
    public function __construct(Repository $repo) {
        $this->repo = $repo;
    }

    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     */
    public function read(array $opts) {
        return [
            'user' => $opts['userid'] < 1 ? null : $this->repo->read_user($opts['userid']),
            'relateduser' => $opts['relateduserid'] < 1 ? null : $this->repo->read_user($opts['relateduserid']),
            'course' => $this->repo->read_course($opts['courseid']),
            'app' => $this->repo->read_site(),
            'info' => (object) [
                'https://moodle.org/' => $this->repo->read_release(),
            ],
            'event' => $opts,
        ];
    }
}
