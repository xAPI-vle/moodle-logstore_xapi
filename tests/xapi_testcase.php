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

namespace Tests;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../vendor/autoload.php');

use \XREmitter\Controller as xapi_controller;
use \XREmitter\Tests\TestRepository as xapi_repository;
use \XREmitter\Tests\TestRemoteLrs as xapi_remote_lrs;
use \MXTranslator\Controller as translator_controller;
use \LogExpander\Controller as moodle_controller;
use \LogExpander\Tests\TestRepository as moodle_repository;
use \Locker\XApi\Statement as LockerStatement;
use \TinCan\Statement as TinCanStatement;

abstract class xapi_testcase extends \advanced_testcase {
    protected $xapicontroller, $moodlecontroller, $translatorcontroller, $cfg;

    /**
     * Sets up the tests.
     * @override PhpUnitTestCase
     */
    public function setup() {
        $this->cfg = (object) [
            'wwwroot' => 'http://www.example.com',
            'release' => '1.0.0'
        ];
        $this->xapicontroller = new xapi_controller(new xapi_repository(new xapi_remote_lrs('', '1.0.1', '', '')));
        $this->moodlecontroller = new moodle_controller(new moodle_repository((object) [], $this->cfg));
        $this->translatorcontroller = new translator_controller();
    }

    public function test_create_event() {
        $input = $this->construct_input();

        $moodleevents = $this->moodlecontroller->create_events([$input]);
        $this->assertNotNull($moodleevents, 'Check that the events exist in the expander controller.');

        // Hack to add Moodle plugin config setting for sendmbox - need to make config function.
        $moodleevents = [array_merge(
            $moodleevents[0],
            ['sendmbox' => false]
        )];

        $translatorevents = $this->translatorcontroller->create_events($moodleevents);
        $this->assertNotNull($translatorevents, 'Check that the events exist in the translator controller.');

        $xapievents = $this->xapicontroller->create_events($translatorevents);
        $this->assertNotNull($xapievents, 'Check that the events exist in the emitter controller.');

        $this->assert_output($input, $xapievents);
    }

    protected function assert_output($input, $output) {
        foreach ($output as $outputpart) {
            $this->assert_valid_xapi_statement((new TinCanStatement($outputpart))->asVersion('1.0.0'));
        }
    }

    protected function assert_valid_xapi_statement($output) {
        $errors = LockerStatement::createFromJson(json_encode($output))->validate();
        $errorsjson = json_encode(array_map(function ($error) {
            return (string) $error;
        }, $errors));
        $this->assertEmpty($errors, $errorsjson);
    }

    protected function construct_input() {
        return [
            'userid' => '1',
            'relateduserid' => '1',
            'courseid' => '1',
            'timecreated' => 1433946701,
            'eventname' => '\core\event\course_viewed'
        ];
    }
}
