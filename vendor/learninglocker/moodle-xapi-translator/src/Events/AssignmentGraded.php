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
            'grade_result' => $opts['grade']->grade,
        ]);
    }
}