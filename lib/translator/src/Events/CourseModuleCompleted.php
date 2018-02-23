<?php


namespace MXTranslator\Events;


class CourseModuleCompleted extends ModuleViewed
{

    public function read(array $opts)
    {
        return   [array_merge(parent::read($opts)[0], [
            'recipe' => 'course_module_completion_updated'

        ])];
    }

}