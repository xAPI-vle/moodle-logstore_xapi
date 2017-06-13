<?php namespace MXTranslator\Tests;
use \MXTranslator\Events\Event as Event;

abstract class UserEventTest extends EventTest {

    protected function constructInput() {
        return array_merge(parent::constructInput(), [
            'user' => $this->constructUser(),
        ]);
    }

    protected function assertOutput($input, $output) {
        parent::assertOutput($input, $output);
        $this->assertUser($input['user'], $output, 'user');
    }
}
