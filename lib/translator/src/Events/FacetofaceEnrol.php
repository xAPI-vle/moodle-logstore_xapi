<?php namespace MXTranslator\Events;

class FacetofaceEnrol extends ModuleViewed {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override ModuleViewed
     */
    public function read(array $opts) {

        $sessionName = 'Session '.$opts['session']->id.' of '.$opts['module']->name;
        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'training_session_enrol',
            'session_url' => $opts['session']->url,
            'session_name' => $sessionName,
            'session_description' => $sessionName,
            'session_type' => 'http://activitystrea.ms/schema/1.0/event',
        ])];
    }
}