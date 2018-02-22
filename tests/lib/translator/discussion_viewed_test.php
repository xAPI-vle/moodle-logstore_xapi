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

use \MXTranslator\Events\DiscussionViewed as Event;

class discussion_viewed_test extends module_viewed_test {
    protected static $recipename = 'discussion_viewed';

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
            'discussion' => $this->construct_discussion(),
        ]);
    }

    private function construct_discussion() {
        return (object) [
            'url' => 'http://www.example.com/discussion_url',
            'name' => 'Test discussion_name',
            'type' => 'moodle_discussion',
            'ext' => 'discussion_ext',
            'ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_discussion',
        ];
    }

    protected function assert_output($input, $output) {
        parent::assert_output($input, $output);
        $this->assert_discussion($input['discussion'], $output, 'discussion');
    }

    private function assert_discussion($input, $output, $type) {
        $extkey = 'http://lrs.learninglocker.net/define/extensions/moodle_discussion';
        $this->assertEquals($input->url, $output[$type.'_url']);
        $this->assertEquals($input->name, $output[$type.'_name']);
        $this->assertEquals('A Moodle discussion.', $output[$type.'_description']);
        $this->assertEquals(static::$xapitype.$input->type, $output[$type.'_type']);
        $this->assertEquals($input, $output[$type.'_ext']);
        $this->assertEquals($extkey, $output[$type.'_ext_key']);
    }
}
