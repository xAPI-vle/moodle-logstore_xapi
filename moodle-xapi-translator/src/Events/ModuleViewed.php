<?php namespace MXTranslator\Events;

class ModuleViewed extends CourseViewed {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override CourseViewed
     */
    public function read(array $opts) {
        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'module_viewed',
            'module_url' => $opts['module']->url,
            'module_name' => $opts['module']->name,
            'module_description' => isset($opts['module']->intro) ? $opts['module']->intro : 'A module',
            'module_type' => static::$xapiType.$opts['module']->type,
            'module_ext' => $opts['module'],
            'module_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_module'
        ])];
    }
}
