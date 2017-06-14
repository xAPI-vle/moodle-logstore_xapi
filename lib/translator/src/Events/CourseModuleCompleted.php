<?php
/**
 * Created by PhpStorm.
 * User: nikolay.mikov
 * Date: 2.6.2017 г.
 * Time: 18:12
 */

namespace MXTranslator\Events;


class CourseModuleCompleted extends CourseViewed
{

    public function read(array $opts)
    {

//        echo '<h1>TRANSLATOR</h1><br>';
//        var_dump($opts);



        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'course_module_completion_updated',
        ])];

//        $translator_arr =  [array_merge(parent::read($opts)[0], [
//            'recipe' => 'course_module_completion_updated',
//        ])];
//
//        echo "Translator merge";
//
//        var_dump($translator_arr);




    }

}