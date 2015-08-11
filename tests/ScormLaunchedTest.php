<?php namespace Tests;

class ScormLaunchedTest extends TestCase {
    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'objecttable' => 'scorm_scoes',
            'objectid' => 1,
            'eventname' => '\mod_scorm\event\sco_launched',
        ]);
    }
}