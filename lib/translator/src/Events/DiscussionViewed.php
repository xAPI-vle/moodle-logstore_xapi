<?php namespace MXTranslator\Events;

class DiscussionViewed extends ModuleViewed {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override ModuleViewed
     */
    public function read(array $opts) {
        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'discussion_viewed',
            'discussion_url' => $opts['discussion']->url,
            'discussion_name' => $opts['discussion']->name,
            'discussion_description' => 'A Moodle discussion.',
            'discussion_type' => static::$xapiType.$opts['discussion']->type,
            'discussion_ext' => $opts['discussion'],
            'discussion_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_discussion',
        ])];
    }
}