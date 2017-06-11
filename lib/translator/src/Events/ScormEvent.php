<?php namespace MXTranslator\Events;

class ScormEvent extends ModuleViewed {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override CourseViewed
     */
    public function read(array $opts) {
        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'scorm_event',
            'scorm_url' => $opts['module']->url,
            'scorm_name' => $opts['module']->name,
            'scorm_scoes_id' => $opts['scorm_scoes']->id,
            'scorm_scoes_type' => 'http://adlnet.gov/expapi/activities/lesson',
            'scorm_scoes_url' => $opts['module']->url,
            'scorm_scoes_name' => $opts['scorm_scoes']->title,
            'scorm_scoes_description' => $opts['scorm_scoes']->title,
            'scorm_attempt' => $opts['cmi_data']['attemptid'],
            'scorm_status' => $opts['scorm_scoes_track']['status'],
        ])];
    }
}