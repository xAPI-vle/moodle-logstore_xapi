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

use \MXTranslator\Repository as Repository;
use \stdClass as PhpObj;

class Event extends PhpObj {
    protected static $xapitype = 'http://lrs.learninglocker.net/define/type/moodle/';

    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     */
    public function read(array $opts) {
        $appname = $opts['app']->fullname ?: 'A Moodle site';

        return [[
            'user_id' => $opts['user']->id,
            'user_email' => $opts['user']->email,
            'user_url' => $opts['user']->url,
            'user_name' => $opts['user']->fullname,
            'context_lang' => is_null($opts['course']->lang)
                || $opts['course']->lang == '' ? "en" : $opts['course']->lang,
            'context_platform' => 'Moodle',
            'context_ext' => $opts['event'],
            'context_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_logstore_standard_log',
            'context_info' => $opts['info'],
            'time' => date('c', $opts['event']['timecreated']),
            'app_url' => $opts['app']->url,
            'app_name' => $appname,
            'app_description' => strip_tags($opts['app']->summary) ?: $appname,
            'app_type' => 'http://id.tincanapi.com/activitytype/site',
            'app_ext' => $opts['app'],
            'sendmbox' => $opts['sendmbox'],
            'app_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_course',
            'source_url' => 'http://moodle.org',
            'source_name' => 'Moodle',
            'source_description' => 'Moodle is a open source learning platform designed to provide educators,'
                .' administrators and learners with a single robust, secure and integrated system'
                .' to create personalised learning environments.',
            'source_type' => 'http://id.tincanapi.com/activitytype/source'
        ]];
    }
}
