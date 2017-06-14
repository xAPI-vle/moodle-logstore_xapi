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

//        echo '<h1>EXPANDER</h1><br>';
//        var_dump($opts);


        return array_merge(parent::read($opts), [
            'module' => $this->repo->readObject($opts['objectid'], $opts['objecttable'])
        ]);

//        $expander_arr = array_merge(parent::read($opts), [
//            'module' => $this->repo->readObject($opts['objectid'], $opts['objecttable'])
//        ]);
//
//        echo 'Expander merge';
//
//        var_dump($expander_arr);

    }
}