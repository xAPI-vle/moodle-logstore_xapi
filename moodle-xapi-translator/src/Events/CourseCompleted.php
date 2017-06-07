<?php
/**
 * Created by PhpStorm.
 * User: lee.kirkland
 * Date: 5/2/2016
 * Time: 4:33 PM
 */

namespace MXTranslator\Events;


/**
 * Class CourseCompleted
 * @package MXTranslator\Events
 */
class CourseCompleted extends CourseViewed
{
    /**
     * overides CourseViewed recipe. 
     * @param array $opts
     * @return array
     */
    public function read(array $opts) {
        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'course_completed',
        ])];
    }
}