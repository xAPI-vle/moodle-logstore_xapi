<?php


namespace LogExpander\Events;


class CourseModuleCompleted extends  Event
{
    public function read(array $opts)
    {


        $contextinstanceid = $opts['contextinstanceid'];

        $course_module = $this->repo->readModuleByContext($contextinstanceid);
        $course_module_type = $this->repo->readModuleType($course_module->module);

        return array_merge(parent::read($opts), [
            'module' => $this->repo->readModule($course_module->instance, $course_module_type->name)
        ]);

    }
}