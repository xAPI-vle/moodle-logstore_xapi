<?php namespace Tests;

class ModuleViewedTest extends TestCase {
    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => 'page',
            'objectid' => 1,
            'eventname' => '\mod_page\event\course_module_viewed',
        ]);
    }
}