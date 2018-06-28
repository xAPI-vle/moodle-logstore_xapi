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

namespace MXTranslator\Events;

defined('MOODLE_INTERNAL') || die();

class FeedbackQuestionSubmitted extends FeedbackSubmitted {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override AttemtStarted
     */
    public function read(array $opts) {
        $translatorevents = [];

        $feedback = parent::parse_feedback($opts);

        // Push question statements to $translatorevents['events'].
        foreach ($feedback->questions as $questionid => $questionattempt) {
            array_push(
                $translatorevents,
                $this->question_statement(
                    parent::read($opts)[0],
                    $questionattempt
                )
            );
        }

        return $translatorevents;
    }

    /**
     * Build a translator event for an individual question attempt.
     * @param [String => Mixed] $template
     * @param PHPObj $questionattempt
     * @param PHPObj $question
     * @return [String => Mixed]
     */
    protected function question_statement($template, $questionattempt) {

        $translatorevent = [
            'recipe' => 'attempt_question_completed',
            'question_attempt_ext' => $questionattempt,
            'question_attempt_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_feedback_question_attempt',
            'question_ext' => $questionattempt->question,
            'question_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_feedback_question',
            'question_name' => $questionattempt->question->name ?: 'A Moodle feedback question',
            'question_description' => $questionattempt->question->name ?: 'A Moodle feedback question',
            'question_url' => $questionattempt->question->url,
            'attempt_score_scaled' => $questionattempt->score->scaled,
            'attempt_score_raw' => $questionattempt->score->raw,
            'attempt_score_min' => $questionattempt->score->min,
            'attempt_score_max' => $questionattempt->score->max,
            'attempt_response' => $questionattempt->response,
            'attempt_success' => null,
            'attempt_completed' => true,
            'interaction_correct_responses' => null,
            'attempt_ext' => null // For questions the attempt extension is not used, so there's no need to pass that bulk of data.
        ];

        switch ($questionattempt->question->typ) {
            case 'multichoice':
                $translatorevent['interaction_type'] = 'choice';
                $translatorevent['interaction_choices'] = (object)[];
                foreach ($questionattempt->options as $index => $option) {
                    $translatorevent['interaction_choices']->$index = $option->description;
                }
                break;
            case 'multichoicerated':
                $translatorevent['interaction_type'] = 'likert';
                $translatorevent['interaction_scale'] = (object)[];
                foreach ($questionattempt->options as $index => $option) {
                    $translatorevent['interaction_scale']->$index = $option->description;
                }
                break;
            case 'textfield':
                $translatorevent['interaction_type'] = 'fill-in';
                break;
            case 'textarea':
                $translatorevent['interaction_type'] = 'long-fill-in';
                break;
            case 'numeric':
                $translatorevent['interaction_type'] = 'numeric';
                break;
            case 'info':
                $translatorevent['interaction_type'] = 'other';
                break;
            default:
                // Unsupported type.
                break;
        }

        return array_merge($template, $translatorevent);
    }

}
