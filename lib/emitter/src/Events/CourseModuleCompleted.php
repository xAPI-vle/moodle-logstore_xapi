<?php
/**
 * Created by PhpStorm.
 * User: nikolay.mikov
 * Date: 5.6.2017 г.
 * Time: 11:50
 */

namespace XREmitter\Events;


class CourseModuleCompleted extends Event
{
    /**
     * Sets the language equivalent for completed.
     * @var array
     */
    protected static $verbDisplay = [
        'en' => 'completed'
    ];
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts)
    {

        return array_merge_recursive(parent::read($opts), [
            'verb' => [
                'id' => 'http://adlnet.gov/expapi/verbs/completed',
                'display' => $this->readVerbDisplay($opts),
            ],
            'object' => $this->readModule($opts),

        ]);

    }
}

