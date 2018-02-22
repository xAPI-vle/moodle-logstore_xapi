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

namespace XREmitter\Tests;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../vendor/autoload.php');

use \PHPUnit_Framework_TestCase as PhpUnitTestCase;
use \XREmitter\Events\Event as Event;
use \Locker\XApi\Statement as Statement;

abstract class event_test extends \advanced_testcase {
    protected static $xapitype = 'http://id.tincanapi.com/activitytype/lms';
    protected static $recipename;

    /**
     * Sets up the tests.
     * @override PhpUnitTestCase
     */
    public function setup() {
        // Empty.
    }

    /**
     * Tests the read method of the Event.
     */
    public function test_read() {
        $input = $this->construct_input();
        $output = $this->event->read($input);
        $this->assert_output($input, $output);
        $this->create_example_file($output);
    }

    protected function construct_input() {
        return array_merge(
            $this->construct_user('user'),
            $this->construct_log(),
            $this->construct_app(),
            $this->construct_source(),
            ['recipe' => static::$recipename]
        );
    }

    protected function construct_user($type) {
        return [
            $type.'_id' => '1',
            $type.'_url' => 'http://www.example.com/'.$type.'_url',
            $type.'_name' => 'Test '.$type.'_name',
        ];
    }

    private function construct_log() {
        return [
            'context_lang' => 'en',
            'context_platform' => 'Moodle',
            'context_info' => (object) [
                'https://moodle.org/' => '1.0.0'
            ],
            'context_ext' => [
                'test_context_ext_key' => 'test_context_ext_value',
            ],
            'context_ext_key' => 'http://www.example.com/context_ext_key',
            'time' => '2015-01-01T01:00Z',
        ];
    }

    protected function construct_object($type, $xapitype = null) {
        if (is_null($xapitype)) {
            $xapitype = static::$xapitype . $type;
        }
        return [
            $type.'_url' => 'http://www.example.com/'.$type.'_url',
            $type.'_name' => 'Test '.$type.'_name',
            $type.'_description' => 'Test '.$type.'_description',
            $type.'_type' => $xapitype,
            $type.'_ext' => [
                'test_'.$type.'_ext_key' => 'test_'.$type.'_ext_value',
            ],
            $type.'_ext_key' => 'http://www.example.com/'.$type.'_ext_key',
        ];
    }

    protected function construct_app() {
        $type = 'app';
        return [
            $type.'_url' => 'http://www.example.com/'.$type.'_url',
            $type.'_name' => 'Test '.$type.'_name',
            $type.'_description' => 'Test '.$type.'_description',
            $type.'_type' => 'http://id.tincanapi.com/activitytype/site',
            $type.'_ext' => [
                'test_'.$type.'_ext_key' => 'test_'.$type.'_ext_value',
            ],
            $type.'_ext_key' => 'http://www.example.com/'.$type.'_ext_key',
        ];
    }

    protected function construct_source() {
        $type = 'source';
        return [
            $type.'_url' => 'http://www.example.com/'.$type.'_url',
            $type.'_name' => 'Test '.$type.'_name',
            $type.'_description' => 'Test '.$type.'_description',
            $type.'_type' => 'http://id.tincanapi.com/activitytype/source'
        ];
    }

    protected function construct_attempt() {
        return [
            'attempt_url' => 'http://www.example.com/attempt_url',
            'attempt_type' => static::$xapitype . 'attempt',
            'attempt_ext' => [
                'test_attempt_ext_key' => 'test_attempt_ext_value',
            ],
            'attempt_ext_key' => 'http://www.example.com/attempt_ext_key',
            'attempt_name' => 'Test attempt_name',
        ];
    }

    protected function construct_discussion() {
        return [
            'discussion_url' => 'http://www.example.com/discussion_url',
            'discussion_name' => 'A Forum Post',
            'discussion_description' => 'A description of the forum',
            'discussion_type' => static::$xapitype . 'discussion',
            'discussion_ext_key' => 'http://www.example.com/attempt_ext_key',
            'discussion_ext' => [
                'discussion_ext_key' => 'discussion_ext_value',
            ],
        ];
    }

    protected function assert_output($input, $output) {
        $this->assert_user($input, $output['actor'], 'user');
        $this->assert_object('app', $input, $output['context']['contextActivities']['grouping'][0]);
        $this->assert_object('source', $input, $output['context']['contextActivities']['category'][0]);
        $this->assert_log($input, $output);
        $this->assert_info(
            $input['context_info'],
            $output['context']['extensions']['http://lrs.learninglocker.net/define/extensions/info']
        );
        $this->assert_valid_xapi_statement($output);
    }

    protected function assert_valid_xapi_statement($output) {
        $errors = Statement::createFromJson(json_encode($output))->validate();
        $errorsjson = json_encode(array_map(function ($error) {
            return (string) $error;
        }, $errors));
        $this->assertEmpty($errors, $errorsjson);
    }

    protected function assert_info($input, $output) {
        $this->assertEquals(
            $input->{'https://moodle.org/'},
            $output->{'https://moodle.org/'}
        );
    }

    protected function assert_user($input, $output, $type) {
        $this->assertEquals($input[$type.'_id'], $output['account']['name']);
        $this->assertEquals($input[$type.'_url'], $output['account']['homePage']);
        $this->assertEquals($input[$type.'_name'], $output['name']);
    }

    protected function assert_log($input, $output) {
        $actualcontext = $output['context'];
        $this->assertEquals($input['context_lang'], $actualcontext['language']);
        $this->assertEquals($input['context_platform'], $actualcontext['platform']);
        $this->assertArrayHasKey($input['context_ext_key'], $actualcontext['extensions']);
        $this->assertEquals($input['context_ext'], $actualcontext['extensions'][$input['context_ext_key']]);
        $this->assertEquals($input['time'], $output['timestamp']);
    }

    protected function assert_object($type, $input, $output) {
        $this->assertEquals($input[$type.'_url'], $output['id']);
        $this->assertEquals($input[$type.'_name'], $output['definition']['name'][$input['context_lang']]);
        $this->assertEquals($input[$type.'_type'], $output['definition']['type']);
        $this->assertEquals($input[$type.'_description'], $output['definition']['description'][$input['context_lang']]);
    }

    protected function assert_verb($verbid, $verbname, $output) {
        $this->assertEquals($verbid, $output['id']);
        $this->assertEquals($verbname, $output['display']['en']);
    }

    protected function assert_attempt($input, $output) {
        $this->assertEquals($input['attempt_url'], $output['id']);
        $this->assertEquals($input['attempt_name'], $output['definition']['name'][$input['context_lang']]);
        $this->assertEquals($input['attempt_type'], $output['definition']['type']);
        $this->assertArrayHasKey($input['attempt_ext_key'], $output['definition']['extensions']);
        $this->assertEquals($input['attempt_ext'], $output['definition']['extensions'][$input['attempt_ext_key']]);
    }

    protected function assert_component_list($input, $output, $lang) {
        foreach ($input as $id => $description) {
            $outputid = 'Matching Id not found.';
            $outputdescription = null;
            foreach ($output as $outputitem) {
                if ($outputitem->id == $id) {
                    $outputid = $outputitem->id;
                    $outputdescription = $outputitem->description[$lang];
                }
            }
            $this->assertEquals($id, $outputid);
            $this->assertEquals($description, $outputdescription);
        }
    }

    protected function create_example_file($output) {
        $classarray = explode('\\', get_class($this));
        $eventname = str_replace('_test', '', array_pop($classarray));
        $examplefile = __DIR__ . '/../../../lib/emitter/docs/examples/' . $eventname . '.json';
        file_put_contents($examplefile, json_encode($output, JSON_PRETTY_PRINT));
    }
}
