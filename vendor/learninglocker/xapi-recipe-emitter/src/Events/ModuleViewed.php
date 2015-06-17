<?php namespace XREmitter\Events;

class ModuleViewed extends Viewed {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return array_merge_recursive(parent::read($opts), [
            'object' => $this->readModule($opts, 'module', 'http://adlnet.gov/expapi/activities/module'),
            'context' => [
                'contextActivities' => [
                    'grouping' => [
                        $this->readCourse($opts, 'course', 'http://adlnet.gov/expapi/activities/course'),
                    ],
                ],
            ],
        ]);
    }
}