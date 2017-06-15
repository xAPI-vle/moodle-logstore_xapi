<?php namespace LogExpander;
use \stdClass as PhpObj;
use Exception;

class Repository extends PhpObj {
    protected $store;
    protected $cfg;

    /**
     * Constructs a new Repository.
     * @param $store
     * @param PhpObj $cfg
     */
    public function __construct($store, PhpObj $cfg) {
        $this->store = $store;
        $this->cfg = $cfg;
    }

    /**
     * Reads an object from the store with the given type and query.
     * @param String $type
     * @param [String => Mixed] $query
     * @throws Exception if the record was not found
     * @return PhpObj
     */
    protected function readStoreRecord($type, array $query) {
        $model = $this->store->get_record($type, $query);
        if ($model === false) {
            throw new Exception('Record not found.');
        }
        return $model;
    }

    /**
     * Reads an array of objects from the store with the given type and query.
     * @param String $type
     * @param [String => Mixed] $query
     * @return PhpArr
     */
    protected function readStoreRecords($type, array $query) {
        $model = $this->store->get_records($type, $query);
        return $model;
    }

    /**
     * Calls the Moodle core fullname function
     * @param PHPObj $user
     * @return Str
     */
    protected function fullname($user) {
        return fullname($user);
    }

    /**
     * Reads an object from the store with the given id.
     * @param String $id
     * @param String $type
     * @return PhpObj
     */
    public function readObject($id, $type) {
        $model = $this->readStoreRecord($type, ['id' => $id]);
        $model->type = $type;
        return $model;
    }

    /**
     * Reads an object from the store with the given id.
     * @param String $id
     * @param String $type
     * @return PhpObj
     */
    public function readModule($id, $type) {
        $model = $this->readObject($id, $type);
        $module = $this->readStoreRecord('modules', ['name' => $type]);
        $courseModule = $this->readStoreRecord('course_modules', [
            'instance' => $id,
            'module' => $module->id,
            'course' => $model->course
        ]);
        $model->url = $this->cfg->wwwroot . '/mod/'.$type.'/view.php?id=' . $courseModule->id;
        return $model;
    }


    /**
     * Reads module from the store with the given contextinstanceid.
     * @param String $contextinstanceid
     * @return PhpObj
     */

    public function readModuleByContext($contextinstanceid)
    {
        $model = $this->readObject($contextinstanceid,'course_modules');
        return $model;
    }

    /**
     * Reads module from the store with the given cm.
     * @param String $cm
     * @return PhpObj
     */


    public function readModuleType($cm)
    {
        $model = $this->readObject($cm->module,'modules');
        return $model;
    }



    /**
     * Reads a quiz attempt from the store with the given id.
     * @param String $id
     * @return PhpObj
     */
    public function readAttempt($id) {
        $model = $this->readObject($id, 'quiz_attempts');
        $model->url = $this->cfg->wwwroot . '/mod/quiz/attempt.php?attempt='.$id;
        $model->name = 'Attempt '.$id;
        return $model;
    }

    /**
     * Reads question attempts from the store with the given quiz attempt id.
     * @param String $id
     * @return PhpArr
     */
    public function readQuestionAttempts($id) {
        $questionAttempts = $this->readStoreRecords('question_attempts', ['questionusageid' => $id]);
        foreach ($questionAttempts as $questionIndex => $questionAttempt) {
            $questionAttemptSteps = $this->readStoreRecords('question_attempt_steps', ['questionattemptid' => $questionAttempt->id]);
            foreach ($questionAttemptSteps as $stepIndex => $questionAttemptStep) {
                $questionAttemptStep->data = $this->readStoreRecords('question_attempt_step_data', ['attemptstepid' => $questionAttemptStep->id]);
            }
            $questionAttempt->steps = $questionAttemptSteps;
        }
        return $questionAttempts;
    }

    /**
     * Reads questions from the store with the given quiz id.
     * @param String $id
     * @return PhpArr
     */
    public function readQuestions($quizId) {
        $quizSlots = $this->readStoreRecords('quiz_slots', ['quizid' => $quizId]);
        $questions = [];
        foreach ($quizSlots as $index => $quizSlot) {
            try {
                $question = $this->readStoreRecord('question', ['id' => $quizSlot->questionid]);
                $question->answers = $this->readStoreRecords('question_answers', ['question' => $question->id]);
                $question->url = $this->cfg->wwwroot . '/mod/question/question.php?id='.$question->id;

                if ($question->qtype == 'numerical') {
                    $question->numerical = (object)[
                        'answers' => $this->readStoreRecords('question_numerical', ['question' => $question->id]),
                        'options' => $this->readStoreRecord('question_numerical_options', ['question' => $question->id]),
                        'units' => $this->readStoreRecords('question_numerical_units', ['question' => $question->id])
                    ];
                } else if ($question->qtype == 'match') {
                    $question->match = (object)[
                        'options' => $this->readStoreRecord('qtype_match_options', ['questionid' => $question->id]),
                        'subquestions' => $this->readStoreRecords('qtype_match_subquestions', ['questionid' => $question->id])
                    ];
                } else if (strpos($question->qtype, 'calculated') === 0) {
                    $question->calculated = (object)[
                        'answers' => $this->readStoreRecords('question_calculated', ['question' => $question->id]),
                        'options' => $this->readStoreRecord('question_calculated_options', ['question' => $question->id])
                    ];
                } else if ($question->qtype == 'shortanswer') {
                    $question->shortanswer = (object)[
                        'options' => $this->readStoreRecord('qtype_shortanswer_options', ['questionid' => $question->id])
                    ];
                }

                $questions[$question->id] = $question;
            }
            catch (\Exception $e) {
                // Question not found; maybe it was deleted since the event.
                // Don't add the question to the list, but also don't block the attempt event.
            }
        }

        return $questions;
    }

    /**
     * Reads  grade metadata from the store with the given type and id.
     * @param String $id
     * @param String $type
     * @return PhpObj
     */
    public function readGradeItems($id, $type) {
        return $this->readStoreRecord('grade_items', ['itemmodule' => $type, 'iteminstance' => $id]);
    }

    /**
     * Reads assignemnt grade comment from the store for a given grade and assignment id
     * @param String $id
     * @return PhpObj
     */
    public function readGradeComment($gradeId, $assignmentId) {
        $model = $this->readStoreRecord(
            'assignfeedback_comments',
            [
                'assignment' => $assignmentId,
                'grade' => $gradeId
            ]
        );
        return $model;
    }

    /**
     * Reads a feedback attempt from the store with the given id.
     * @param String $id
     * @return PhpObj
     */
    public function readFeedbackAttempt($id) {
        $model = $this->readObject($id, 'feedback_completed');
        $model->url = $this->cfg->wwwroot . '/mod/feedback/complete.php?id='.$id;
        $model->name = 'Attempt '.$id;
        $model->responses = $this->readStoreRecords('feedback_value', ['completed' => $id]);
        return $model;
    }

    /**
     * Reads questions from the store with the given feedback id.
     * @param String $id
     * @return PhpArr
     */
    public function readFeedbackQuestions($id) {
        $questions = $this->readStoreRecords('feedback_item', ['feedback' => $id]);
        $expandedQuestions = [];
        foreach ($questions as $index => $question) {
            $expandedQuestion = $question;
            $expandedQuestion->template = $this->readStoreRecord('feedback_template', ['id' => $question->template]);
            $expandedQuestion->url = $this->cfg->wwwroot . '/mod/feedback/edit_item.php?id='.$question->id;
            $expandedQuestions[$index] = $expandedQuestion;
        }
        return $expandedQuestions;
    }

    /**
     * Reads a course from the store with the given id.
     * @param String $id
     * @return PhpObj
     */
    public function readCourse($id) {
        if ($id == 0) {
            $courses = $this->store->get_records('course',array());

            //since get_records will return the ids as Key values for the array,
            //just use key to find the first id in the course table for the index page
            $id = key($courses);
        }
        $model = $this->readObject($id, 'course');
        $model->url = $this->cfg->wwwroot.($id > 0 ? '/course/view.php?id=' . $id : '');
        return $model;
    }

    /**
     * Reads a user from the store with the given id.
     * @param String $id
     * @return PhpObj
     */
    public function readUser($id) {
        $model = $this->readObject($id, 'user');
        $model->url = $this->cfg->wwwroot;
        $model->fullname = $this->fullname($model);
        if (isset($model->password)){
             unset($model->password);
        }
        if (isset($model->secret)){
             unset($model->secret);
        }
        if (isset($model->lastip)){
             unset($model->lastip);
        }
        return $model;
    }

    /**
     * Reads a discussion from the store with the given id.
     * @param String $id
     * @return PhpObj
     */
    public function readDiscussion($id) {
        $model = $this->readObject($id, 'forum_discussions');
        $model->url = $this->cfg->wwwroot . '/mod/forum/discuss.php?d=' . $id;
        return $model;
    }

    /**
     * Reads the Moodle release number.
     * @return String
     */
    public function readRelease() {
        return $this->cfg->release;
    }

    /**
     * Reads the Moodle site
     * @return PhpObj
     */
    public function readSite() {
        $model = $this->readCourse(1);
        $model->url = $this->cfg->wwwroot;
        $model->type = "site";
        return $model;
    }

    /**
     * Reads a face to face session
     * @return PhpObj
     */
    public function readFacetofaceSession($id) {
        $model = $this->readObject($id, 'facetoface_sessions');
        $model->dates = $this->readStoreRecords('facetoface_sessions_dates', ['sessionid' => $id]);
        $model->url = $this->cfg->wwwroot . '/mod/facetoface/signup.php?s=' . $id;
        return $model;
    }

    /**
     * Reads face to face session signups
     * @return PhpObj
     */
    public function readFacetofaceSessionSignups($sessionid, $timecreated) {
        $signups = $this->readStoreRecords('facetoface_signups', ['sessionid' => $sessionid]);

        foreach ($signups as $index => $signup) {
            $signups[$index]->statuses = $this->readStoreRecords('facetoface_signups_status', ['signupid' => $signup->id]);
            $signups[$index]->attendee = $this->readUser($signup->userid);
        }

        return $signups;
    }

    /**
     * Reads Scorm tracking data
     * @return PhpObj
     */
    public function readScormScoesTrack($userid, $scormid, $scoid, $attempt) {
        $trackingValues = [];
        $scormTracking = $this->readStoreRecords('scorm_scoes_track', [
            'userid' => $userid,
            'scormid'=> $scormid,
            'scoid' => $scoid,
            'attempt' => $attempt
        ]);

        foreach ($scormTracking as $st) {
            if ($st->element == 'cmi.core.score.min') {
                $trackingValues['scoremin'] = $st->value;
            } else if ($st->element == 'cmi.core.score.max') {
                $trackingValues['scoremax'] = $st->value;
            } else if ($st->element == 'cmi.core.lesson_status') {
                $trackingValues['status'] = $st->value;
            }
        }

        return $trackingValues;
    }

    /**
     * Reads a scorm scoes
     * @return PhpObj
     */
    public function readScormScoes($scoid) {
        $model = $this->readObject($scoid, 'scorm_scoes');
        return $model;
    }
}
