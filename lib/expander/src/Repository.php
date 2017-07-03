<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace LogExpander;

defined('MOODLE_INTERNAL') || die();

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
    protected function read_store_record($type, array $query) {
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
    protected function read_store_records($type, array $query) {
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
    public function read_object($id, $type) {
        $model = $this->read_store_record($type, ['id' => $id]);
        $model->type = $type;
        return $model;
    }

    /**
     * Reads an object from the store with the given id.
     * @param String $id
     * @param String $type
     * @return PhpObj
     */
    public function read_module($id, $type) {
        $model = $this->read_object($id, $type);
        $module = $this->read_store_record('modules', ['name' => $type]);
        $coursemodule = $this->read_store_record('course_modules', [
            'instance' => $id,
            'module' => $module->id,
            'course' => $model->course
        ]);
        $model->url = $this->cfg->wwwroot . '/mod/'.$type.'/view.php?id=' . $coursemodule->id;
        return $model;
    }

    /**
     * Reads a quiz attempt from the store with the given id.
     * @param String $id
     * @return PhpObj
     */
    public function read_attempt($id) {
        $model = $this->read_object($id, 'quiz_attempts');
        $model->url = $this->cfg->wwwroot . '/mod/quiz/attempt.php?attempt='.$id;
        $model->name = 'Attempt '.$id;
        return $model;
    }

    /**
     * Reads question attempts from the store with the given quiz attempt id.
     * @param String $id
     * @return PhpArr
     */
    public function read_question_attempts($id) {
        $questionattempts = $this->read_store_records('question_attempts', ['questionusageid' => $id]);
        foreach ($questionattempts as $questionindex => $questionattempt) {
            $questionattemptsteps = $this->read_store_records(
                'question_attempt_steps',
                ['questionattemptid' => $questionattempt->id]
            );
            foreach ($questionattemptsteps as $stepindex => $questionattemptstep) {
                $questionattemptstep->data = $this->read_store_records(
                    'question_attempt_step_data',
                    ['attemptstepid' => $questionattemptstep->id]
                );
            }
            $questionattempt->steps = $questionattemptsteps;
        }
        return $questionattempts;
    }

    /**
     * Reads questions from the store with the given quiz id.
     * @param string $quizid
     * @return PhpArr
     */
    public function read_questions($quizid) {
        $quizslots = $this->read_store_records('quiz_slots', ['quizid' => $quizid]);
        $questions = [];
        foreach ($quizslots as $index => $quizslot) {
            try {
                $question = $this->read_store_record('question', ['id' => $quizslot->questionid]);
                $question->answers = $this->read_store_records('question_answers', ['question' => $question->id]);
                $question->url = $this->cfg->wwwroot . '/mod/question/question.php?id='.$question->id;

                if ($question->qtype == 'numerical') {
                    $question->numerical = (object)[
                        'answers' => $this->read_store_records('question_numerical', ['question' => $question->id]),
                        'options' => $this->read_store_record('question_numerical_options', ['question' => $question->id]),
                        'units' => $this->read_store_records('question_numerical_units', ['question' => $question->id])
                    ];
                } else if ($question->qtype == 'match') {
                    $question->match = (object)[
                        'options' => $this->read_store_record('qtype_match_options', ['questionid' => $question->id]),
                        'subquestions' => $this->read_store_records('qtype_match_subquestions', ['questionid' => $question->id])
                    ];
                } else if (strpos($question->qtype, 'calculated') === 0) {
                    $question->calculated = (object)[
                        'answers' => $this->read_store_records('question_calculated', ['question' => $question->id]),
                        'options' => $this->read_store_record('question_calculated_options', ['question' => $question->id])
                    ];
                } else if ($question->qtype == 'shortanswer') {
                    $question->shortanswer = (object)[
                        'options' => $this->read_store_record('qtype_shortanswer_options', ['questionid' => $question->id])
                    ];
                }

                $questions[$question->id] = $question;
            } catch (\Exception $e) { // @codingStandardsIgnoreLine
                // Question not found; maybe it was deleted since the event.
                // Don't add the question to the list, but also don't block the attempt event.
            }
        }

        return $questions;
    }

    /**
     * Reads grade metadata from the store with the given type and id.
     * @param string $id
     * @param string $type
     * @return PhpObj
     */
    public function read_grade_items($id, $type) {
        return $this->read_store_record('grade_items', ['itemmodule' => $type, 'iteminstance' => $id]);
    }

    /**
     * Reads assignemnt grade comment from the store for a given grade and assignment id
     * @param string $id
     * @return PhpObj
     */
    public function read_grade_comment($gradeid, $assignmentid) {
        $model = $this->read_store_record(
            'assignfeedback_comments',
            [
                'assignment' => $assignmentid,
                'grade' => $gradeid
            ]
        );
        return $model;
    }

    /**
     * Reads a feedback attempt from the store with the given id.
     * @param String $id
     * @return PhpObj
     */
    public function read_feedback_attempt($id) {
        $model = $this->read_object($id, 'feedback_completed');
        $model->url = $this->cfg->wwwroot . '/mod/feedback/complete.php?id='.$id;
        $model->name = 'Attempt '.$id;
        $model->responses = $this->read_store_records('feedback_value', ['completed' => $id]);
        return $model;
    }

    /**
     * Reads questions from the store with the given feedback id.
     * @param String $id
     * @return PhpArr
     */
    public function read_feedback_questions($id) {
        $questions = $this->read_store_records('feedback_item', ['feedback' => $id]);
        $expandedquestions = [];
        foreach ($questions as $index => $question) {
            $expandedquestion = $question;
            $expandedquestion->template = $this->read_store_record('feedback_template', ['id' => $question->template]);
            $expandedquestion->url = $this->cfg->wwwroot . '/mod/feedback/edit_item.php?id='.$question->id;
            $expandedquestions[$index] = $expandedquestion;
        }
        return $expandedquestions;
    }

    /**
     * Reads a course from the store with the given id.
     * @param String $id
     * @return PhpObj
     */
    public function read_course($id) {
        if ($id == 0) {
            $courses = $this->store->get_records('course', array());

            // Since get_records will return the ids as Key values for the array,
            // just use key to find the first id in the course table for the index page.
            $id = key($courses);
        }
        $model = $this->read_object($id, 'course');
        $model->url = $this->cfg->wwwroot.($id > 0 ? '/course/view.php?id=' . $id : '');
        return $model;
    }

    /**
     * Reads a user from the store with the given id.
     * @param string $id
     * @return PhpObj
     */
    public function read_user($id) {
        $model = $this->read_object($id, 'user');
        $model->url = $this->cfg->wwwroot;
        $model->fullname = $this->fullname($model);
        if (isset($model->password)) {
            unset($model->password);
        }
        if (isset($model->secret)) {
            unset($model->secret);
        }
        if (isset($model->lastip)) {
            unset($model->lastip);
        }
        return $model;
    }

    /**
     * Reads a discussion from the store with the given id.
     * @param String $id
     * @return PhpObj
     */
    public function read_discussion($id) {
        $model = $this->read_object($id, 'forum_discussions');
        $model->url = $this->cfg->wwwroot . '/mod/forum/discuss.php?d=' . $id;
        return $model;
    }

    /**
     * Reads the Moodle release number.
     * @return String
     */
    public function read_release() {
        return $this->cfg->release;
    }

    /**
     * Reads the Moodle site
     * @return PhpObj
     */
    public function read_site() {
        $model = $this->read_course(1);
        $model->url = $this->cfg->wwwroot;
        $model->type = "site";
        return $model;
    }

    /**
     * Reads a face to face session
     * @return PhpObj
     */
    public function read_facetoface_session($id) {
        $model = $this->read_object($id, 'facetoface_sessions');
        $model->dates = $this->read_store_records('facetoface_sessions_dates', ['sessionid' => $id]);
        $model->url = $this->cfg->wwwroot . '/mod/facetoface/signup.php?s=' . $id;
        return $model;
    }

    /**
     * Reads face to face session signups
     * @return PhpObj
     */
    public function read_facetoface_session_signups($sessionid, $timecreated) {
        $signups = $this->read_store_records('facetoface_signups', ['sessionid' => $sessionid]);

        foreach ($signups as $index => $signup) {
            $signups[$index]->statuses = $this->read_store_records('facetoface_signups_status', ['signupid' => $signup->id]);
            $signups[$index]->attendee = $this->read_user($signup->userid);
        }

        return $signups;
    }

    /**
     * Reads Scorm tracking data
     * @return PhpObj
     */
    public function read_scorm_scoes_track($userid, $scormid, $scoid, $attempt) {
        $trackingvalues = [];
        $scormtracking = $this->read_store_records('scorm_scoes_track', [
            'userid' => $userid,
            'scormid' => $scormid,
            'scoid' => $scoid,
            'attempt' => $attempt
        ]);

        foreach ($scormtracking as $st) {
            if ($st->element == 'cmi.core.score.min') {
                $trackingvalues['scoremin'] = $st->value;
            } else if ($st->element == 'cmi.core.score.max') {
                $trackingvalues['scoremax'] = $st->value;
            } else if ($st->element == 'cmi.core.lesson_status') {
                $trackingvalues['status'] = $st->value;
            }
        }

        return $trackingvalues;
    }

    /**
     * Reads a scorm scoes
     * @return PhpObj
     */
    public function read_scorm_scoes($scoid) {
        $model = $this->read_object($scoid, 'scorm_scoes');
        return $model;
    }
}
