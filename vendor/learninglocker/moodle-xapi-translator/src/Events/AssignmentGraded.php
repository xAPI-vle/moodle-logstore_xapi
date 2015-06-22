<?php namespace MXTranslator\Events;

class AssignmentGraded extends ModuleViewed {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override ModuleViewed
     */
    public function read(array $opts) {
        return array_merge(parent::read($opts), [
            'recipe' => 'assignment_graded',
            'graded_user_id' => $opts['graded_user']->id,
            'graded_user_url' => $opts['graded_user']->url,
            'graded_user_name' => $opts['graded_user']->username,
            'grade_result' => $opts['grade']->grade,
        ]);
    }
}