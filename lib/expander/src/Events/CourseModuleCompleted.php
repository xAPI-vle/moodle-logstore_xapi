<?php
/**
 * Created by PhpStorm.
 * User: nikolay.mikov
 * Date: 5.6.2017 г.
 * Time: 11:32
 */

namespace LogExpander\Events;


class CourseModuleCompleted extends  Event
{
    public function read(array $opts)
    {

        global $DB;

        $moduleid = $opts['contextinstanceid'];

        $cm = $DB->get_record('course_modules',array('id' =>$moduleid));
        $cmtype = $DB->get_record('modules',array('id' =>$cm->module));


        return array_merge(parent::read($opts), [
            'module' => $this->repo->readModule($cm->instance, $cmtype->name)
        ]);

    }
}