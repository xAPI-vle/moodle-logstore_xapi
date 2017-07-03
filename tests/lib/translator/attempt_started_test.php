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

namespace MXTranslator\Tests;

defined('MOODLE_INTERNAL') || die();

use \MXTranslator\Events\AttemptStarted as Event;

class attempt_started_test extends module_viewed_test {
    protected static $recipename = 'attempt_started';

    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        parent::setup();
        $this->event = new Event();
    }

    protected function construct_input() {
        return array_merge(parent::construct_input(), [
            'attempt' => $this->construct_attempt(),
        ]);
    }

    private function construct_attempt() {
        return (object) [
            'url' => 'http://www.example.com/attempt_url',
            'name' => 'Test attempt_name',
            'type' => 'moodle_attempt',
            'timestart' => 1433946701,
            'timefinish' => 1433946701,
            'sumgrades' => 1,
            'state' => 'finished',
        ];
    }

    protected function assert_output($input, $output) {
        parent::assert_output($input, $output);
        $this->assert_attempt($input['attempt'], $output);
    }

    protected function assert_attempt($input, $output) {
        $extkey = 'http://lrs.learninglocker.net/define/extensions/moodle_attempt';
        $this->assertEquals($input->url, $output['attempt_url']);
        $this->assertEquals($input->name, $output['attempt_name']);
        $this->assertEquals(static::$xapitype.$input->type, $output['attempt_type']);
        $this->assertEquals($input, $output['attempt_ext']);
        $this->assertEquals($extkey, $output['attempt_ext_key']);
    }
}
