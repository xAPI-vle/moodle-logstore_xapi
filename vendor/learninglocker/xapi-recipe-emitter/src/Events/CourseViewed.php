<?php namespace XREmitter\Events;

class CourseViewed extends Viewed {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return array_merge(parent::read($opts), [
            'object' => $this->readCourse($opts),
        ]);
    }
}