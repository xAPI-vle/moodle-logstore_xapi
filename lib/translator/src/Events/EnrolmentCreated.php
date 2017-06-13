<?php namespace MXTranslator\Events;

class EnrolmentCreated extends CourseViewed {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override CourseViewed
     */
    public function read(array $opts) {
        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'enrolment_created',
            'user_id' => $opts['relateduser']->id,
            'user_url' => $opts['relateduser']->url,
            'user_name' => $opts['relateduser']->fullname,
            'instructor_id' => $opts['user']->id,
            'instructor_url' => $opts['user']->url,
            'instructor_name' => $opts['user']->fullname,
        ])];
    }
}