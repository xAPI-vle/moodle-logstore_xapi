<?php


namespace LogExpander\Events;


class CourseModuleCompleted extends  Event
{
    public function read(array $opts)
    {


        $contextinstanceid = $opts['contextinstanceid'];

        $cm = $this->repo->readModuleByContext($contextinstanceid);
        $cmtype = $this->repo->readModuleType($cm);

        return array_merge(parent::read($opts), [
            'module' => $this->repo->readModule($cm->instance, $cmtype->name)
        ]);

    }
}