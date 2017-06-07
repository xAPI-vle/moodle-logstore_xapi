<?php namespace MXTranslator\Events;

class AttemptReviewed extends AttemptStarted {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override AttemtStarted
     */
    public function read(array $opts) {
        $seconds = $opts['attempt']->timefinish - $opts['attempt']->timestart;
        $duration = "PT".(string) $seconds."S";
        $scoreRaw = (float) ($opts['attempt']->sumgrades ?: 0);
        $scoreMin = (float) ($opts['grade_items']->grademin ?: 0);
        $scoreMax = (float) ($opts['grade_items']->grademax ?: 0);
        $scorePass = (float) ($opts['grade_items']->gradepass ?: null);
        $success = false;
        //if there is no passing score then success is unknown.
        if ($scorePass == null) {
            $success = null;
        }
        elseif ($scoreRaw >= $scorePass) {
            $success = true;
        }
        //Calculate scaled score as the distance from zero towards the max (or min for negative scores).
        $scoreScaled;
        if ($scoreRaw >= 0) {
            $scoreScaled = $scoreRaw / $scoreMax;
        }
        else {
            $scoreScaled = $scoreRaw / $scoreMin;
        }
        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'attempt_completed',
            'attempt_score_raw' => $scoreRaw,
            'attempt_score_min' => $scoreMin,
            'attempt_score_max' => $scoreMax,
            'attempt_score_scaled' => $scoreScaled,
            'attempt_success' => $success,
            'attempt_completed' => $opts['attempt']->state === 'finished',
            'attempt_duration' => $duration,
        ])];
    }

}