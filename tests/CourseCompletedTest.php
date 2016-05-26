<?php
/**
 * Created by PhpStorm.
 * User: lee.kirkland
 * Date: 5/26/2016
 * Time: 9:29 AM
 */

namespace Tests;


class CourseCompletedTest extends TestCase
{
    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => 'course_completed',
            'objectid' => 1,
            'eventname' => '\core\event\course_completed',
        ]);
    }
}