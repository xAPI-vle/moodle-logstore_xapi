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

namespace MXTranslator\Events;

defined('MOODLE_INTERNAL') || die();

class CourseViewed extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'course_viewed',
            'course_url' => $opts['course']->url,
            'course_name' => $opts['course']->fullname ?: 'A Moodle course',
            'course_description' => strip_tags($opts['course']->summary) ?: 'A Moodle course',
            'course_type' => static::$xapitype.$opts['course']->type,
            'course_ext' => $opts['course'],
            'course_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_course',
        ])];
    }
}
