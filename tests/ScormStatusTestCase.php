<?php namespace Tests;

class ScormStatusTest extends TestCase {
    protected function constructInput() {
        return array_merge(parent::constructInput(), [
          'objecttable' => 'scorm_scoes_track',
          'objectid' => 1,
          'contextinstanceid' => 1,
          'eventname' => '\mod_scorm\event\status_submitted',
          'other' => 'a:3:{s:9:"attemptid";i:2;s:10:"cmielement";s:22:"cmi.core.lesson_status";s:8:"cmivalue";s:6:"failed";}';
        ]);
    }
}
