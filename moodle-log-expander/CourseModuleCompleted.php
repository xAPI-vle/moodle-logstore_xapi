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
    public function read(array $opts) {
        return array_merge(parent::read($opts), [
        ]);
    }
}