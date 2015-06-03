<?php namespace tests;
use logstore_emitter\xapi\activity as activity;

class ExampleTest extends \PHPUnit_Framework_TestCase {
  public function testExample() {
    new activity;
    $this->assertEquals(1, 1);
  }
}
