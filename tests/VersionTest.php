<?php namespace Tests;
use \PHPUnit_Framework_TestCase as PhpUnitTestCase;

class VersionTest extends PhpUnitTestCase {
    protected $expected_versions;

    public function setup() {
        $this->expected_versions = $this->readComposerLock();
    }

    private function readComposerLock() {
        $lock = json_decode(file_get_contents(__DIR__.'/../composer.lock'));
        return array_reduce($lock->packages, function ($carry, $item) {
            $carry[str_replace('/', '-', $item->name)] = str_replace('v', '', $item->version);
            return $carry;
        }, []);
    }

    public function testExpander() {
        $this->assertCorrectVersion('moodle-log-expander');
    }

    public function testTranslator() {
        $this->assertCorrectVersion('moodle-xapi-translator');
    }

    public function testEmitter() {
        $this->assertCorrectVersion('xapi-recipe-emitter');
    }

    private function assertCorrectVersion($repo) {
        $actual = $this->readActualVersion($repo);
        $expected = $this->readExpectedVersion($repo);
        $this->assertEquals($expected, $actual);
    }

    private function readActualVersion($repo) {
        $version = file_get_contents(__DIR__.'/../vendor/learninglocker/'.$repo.'/VERSION');
        return str_replace("\n", "", str_replace("\r", "", $version));
    }

    private function readExpectedVersion($repo) {
        return $this->expected_versions['learninglocker-'.$repo];
    }
}