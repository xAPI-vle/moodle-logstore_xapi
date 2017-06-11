<?php namespace XREmitter\Events;

class ScormScoreRawSubmitted extends ScormEvent {
    protected static $verb_display;

    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return array_merge_recursive(parent::read($opts), [
            'verb' => $this->readScormVerb($opts),
            'result' => [
                'score' => [
                    'raw' => $opts['scorm_score_raw'],
                    'min' => $opts['scorm_score_min'],
                    'max' => $opts['scorm_score_max'],
                    'scaled' => $opts['scorm_score_scaled']
                ],
            ],
        ]);
    }
}
