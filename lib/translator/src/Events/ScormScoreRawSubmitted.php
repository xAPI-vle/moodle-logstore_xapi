<?php namespace MXTranslator\Events;

class ScormScoreRawSubmitted extends ScormEvent {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override ModuleViewed
     */
    public function read(array $opts) {
        $scoreMax = $opts['scorm_scoes_track']['scoremax'];
        $scoreRaw = $opts['cmi_data']['cmivalue'];
        $scoreMin = $opts['scorm_scoes_track']['scoremin'];
        $scoreScaled = NULL;

        $scoreScaled = $scoreRaw >= 0 ? ($scoreRaw / $scoreMax) : ($scoreRaw / $scoreMin);

        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'scorm_scoreraw_submitted',
            'scorm_score_raw' => $scoreRaw,
            'scorm_score_min' => $scoreMin,
            'scorm_score_max' => $scoreMax,
            'scorm_score_scaled' => $scoreScaled,
        ])];
    }
}
