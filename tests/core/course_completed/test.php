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

class course_completed_test extends xapi_testcase {
    protected function get_event() {
        return [
            'userid' => '1',
            'relateduserid' => '1',
            'courseid' => '1',
            'timecreated' => 1433946701,
            'objecttable' => 'course_completed',
            'objectid' => 1,
            'eventname' => '\core\event\course_completed',
        ];
    }

    protected function get_expected_statements() {
        return [
            [
                "actor" => [
                    "name" => "test_fullname",
                    "account" => [
                        "homePage" => "http:\/\/www.example.com",
                        "name" => "1"
                    ]
                ],
                "verb" => [
                    "id" => "http:\/\/id.tincanapi.com\/verb\/viewed",
                    "display" => [
                        "en" => "viewed"
                    ]
                ],
                "object" => [
                    "id" => "http:\/\/www.example.com\/course\/view.php?id=1",
                    "definition" => [
                        "type" => "http:\/\/lrs.learninglocker.net\/define\/type\/moodle\/object",
                        "name" => [
                            "en" => "test_name"
                        ]
                    ]
                ],
                "timestamp" => "2018-03-02T17:28:29+00:00",
                "context" => [
                    "platform" => "Moodle",
                    "language" => "en",
                    "extensions" => [
                        "http:\/\/lrs.learninglocker.net\/define\/extensions\/info" => [
                            "http:\/\/moodle.org" => "1.0.0",
                            "https:\/\/github.com\/xAPI-vle\/moodle-logstore_xapi" => "0.0.0-development",
                            "event_name" => "\\core\\event\\course_viewed",
                            "event_function" => "\\transformer\\events\\core\\course_viewed"
                        ]
                    ],
                    "contextActivities" => [
                        "grouping" => [
                            [
                                "id" => "http:\/\/www.example.com",
                                "definition" => [
                                    "type" => "http:\/\/id.tincanapi.com\/activitytype\/site",
                                    "name" => [
                                        "en" => "test_name"
                                    ]
                                ]
                            ]
                        ],
                        "category" => [
                            [
                                "id" => "http:\/\/moodle.org",
                                "definition" => [
                                    "type" => "http:\/\/id.tincanapi.com\/activitytype\/source",
                                    "name" => [
                                        "en" => "Moodle"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}