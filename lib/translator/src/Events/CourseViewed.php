<?php namespace MXTranslator\Events;

class CourseViewed extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'course_viewed',
            'course_url' => $opts['course']->url,
            'course_name' => $opts['course']->fullname ?: 'A Moodle course',
            'course_description' => strip_tags($opts['course']->summary) ?: 'A Moodle course',
            'course_type' => static::$xapiType.$opts['course']->type,
            'course_ext' => $opts['course'],
            'course_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_course',
        ])];
    }
}