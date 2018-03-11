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

namespace tests;

defined('MOODLE_INTERNAL') || die();

use \PHPUnit_Framework_TestCase as PhpUnitTestCase;
use \Locker\XApi\Statement as LockerStatement;

abstract class xapi_test_case extends PhpUnitTestCase {

    abstract protected function get_event();
    abstract protected function get_expected_statements();

    public function test_create_event() {
        $event = $this->get_event();
        $handler_config = [
            'transformer' => $this->get_transformer_config(),
            'loader' => [
                'loader' => 'log',
                'lrs_endpoint' => '',
                'lrs_username' => '',
                'lrs_password' => '',
                'lrs_max_batch_size' => 1,
            ],
        ];
        $statements = \src\handler($handler_config, [$event]);
        $this->assert_expected_statements($statements);
        foreach ($statements as $statement) {
            $this->assert_valid_xapi_statement($statement);
        }
    }

    protected function get_transformer_config() {
        $DB = (object) [];
        $CFG = (object) [
            'wwwroot' => 'http://www.example.com',
            'release' => '1.0.0',
        ];
        return [
            'source_url' => 'http://moodle.org',
            'source_name' => 'Moodle',
            'source_version' => '1.0.0',
            'source_lang' => 'en',
            'send_mbox' => false,
            'plugin_url' => 'https://github.com/xAPI-vle/moodle-logstore_xapi',
            'plugin_version' => '0.0.0-development',
            'repo' => new \transformer\FakeRepository($DB, $CFG),
        ];
    }

    private function assert_valid_xapi_statement($statement) {
        $errors = LockerStatement::createFromJson(json_encode($statement))->validate();
        $errorsjson = json_encode(array_map(function ($error) {
            return (string) $error;
        }, $errors));
        $this->assertEmpty($errors, $errorsjson);
    }

    private function assert_expected_statements($statements) {
        $expected_statements = $this->get_expected_statements();
        $actual_statements = json_encode($statements, JSON_PRETTY_PRINT);
        $this->assertEquals($actual_statements, $expected_statements);
    }
}