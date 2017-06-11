<?php namespace MXTranslator\Events;

class AssignmentGraded extends ModuleViewed {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override ModuleViewed
     */
    public function read(array $opts) {

        $scoreRaw = (float) ($opts['grade']->grade ?: 0);
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
        else
        {
            $scoreScaled = $scoreRaw / $scoreMin;
        }

        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'assignment_graded',
            'graded_user_id' => $opts['graded_user']->id,
            'graded_user_url' => $opts['graded_user']->url,
            'graded_user_name' => $opts['graded_user']->fullname,
            'grade_score_raw' => $scoreRaw,
            'grade_score_min' => $scoreMin,
            'grade_score_max' => $scoreMax,
            'grade_score_scaled' => $scoreScaled,
            'grade_success' => $success,
            'grade_completed' => true,
            'grade_comment' => strip_tags($opts['grade_comment']),
        ])];
    }
}
