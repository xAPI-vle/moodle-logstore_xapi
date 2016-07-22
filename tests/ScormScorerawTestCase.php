<?php namespace Tests;

class ScormScorerawTest extends TestCase {
    protected function constructInput() {
        return array_merge(parent::constructInput(), [
          'objecttable' => 'scorm_scoes_track',
          'objectid' => 1,
          'contextinstanceid' => 1,
          'eventname' => '\mod_scorm\event\scoreraw_submitted',
          'other' => 'a:3:{s:9:"attemptid";i:1;s:10:"cmielement";s:18:"cmi.core.score.raw";s:8:"cmivalue";s:3:"100";}',
        ]);
    }
}
