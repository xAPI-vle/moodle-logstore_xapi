<?php
/**
 * Created by PhpStorm.
 * User: lee.kirkland
 * Date: 5/20/2016
 * Time: 9:12 AM
 */

namespace LogExpander\Events;


class CourseCompleted extends Event
{
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return array_merge(parent::read($opts), [
        ]);
    }
}