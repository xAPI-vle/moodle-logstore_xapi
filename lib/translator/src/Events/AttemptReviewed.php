<?php namespace MXTranslator\Events;

class AttemptReviewed extends AttemptStarted {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override AttemtStarted
     */
    public function read(array $opts) {
        if(isset($opts['attempt']->timefinish)){
            $seconds = $opts['attempt']->timefinish - $opts['attempt']->timestart;
            $duration = "PT".(string) $seconds."S";
        }
        else{
            $duration = "PT0S";
        }

        $scoreRaw = isset($opts['attempt']->sumgrades) ? $opts['attempt']->sumgrades : 0;
        $scoreMin = (float) ($opts['grade_items']->grademin ?: 0);
        $scoreMax = (float) ($opts['grade_items']->grademax ?: 0);
        $scorePass = (float) ($opts['grade_items']->gradepass ?: null);
        $success = false;
        // If there is no passing score then success is unknown.
        if ($scorePass == null) {
            $success = null;
        }
        elseif ($scoreRaw >= $scorePass) {
            $success = true;
        }

        // It's possible to configure Moodle quizzes such that you can score higher than the maximum grade. 
        // This is not allowed by xAPI, so cap the raw at the min/max. 
        if ($scoreRaw > $scoreMax) {
            $scoreRaw = $scoreMax;
        }
        if ($scoreRaw < $scoreMin) {
            $scoreRaw = $scoreMin;
        }

        // Calculate scaled score as the distance from zero towards the max (or min for negative scores).
        if ($scoreRaw >= 0) {
            $scoreScaled = $scoreRaw / $scoreMax;
        }
        else {
            $scoreScaled = $scoreRaw / $scoreMin;
        }

        //Determine if the attempt was marked finished
        if(isset($opts['attempt']->state)){
            $completedState = $opts['attempt']->state === 'finished';
        }
        else{
            $completedState = false;
        }

        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'attempt_completed',
            'attempt_score_raw' => $scoreRaw,
            'attempt_score_min' => $scoreMin,
            'attempt_score_max' => $scoreMax,
            'attempt_score_scaled' => $scoreScaled,
            'attempt_success' => $success,
            'attempt_completed' => $completedState,
            'attempt_duration' => $duration,
        ])];
    }

}