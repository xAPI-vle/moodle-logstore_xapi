<?php namespace XREmitter\Events;

class AssignmentGraded extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return array_merge_recursive(parent::read($opts), [
            'verb' => [
                'id' => 'http://www.tincanapi.co.uk/verbs/evaluated',
                'display' => [
                    'en-GB' => 'evaluated',
                    'en-US' => 'evaluated',
                ],
            ],
            'result' => [
                'score' => [
                    'raw' => $opts['grade_result'],
                ],
                'completion' => true,
            ],
            'object' => [
                'objectType' => 'Agent',
                'name' => $opts['graded_user_name'],
                'account' => [
                    'homePage' => $opts['graded_user_url'],
                    'name' => $opts['graded_user_id'],
                ],
            ],
            'context' => [
                'contextActivities' => [
                    'grouping' => [
                        $this->readCourse($opts),
                        $this->readModule($opts),
                    ],
                ],
            ],
        ]);
    }
}