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

class FeedbackSubmitted extends ModuleViewed {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override AttemtStarted
     */
    public function read(array $opts) {

        $feedback = $this->parse_feedback($opts);

        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'attempt_completed',
            'attempt_url' => $opts['attempt']->url,
            'attempt_type' => static::$xapitype.$opts['attempt']->type,
            'attempt_ext' => $opts['attempt'],
            'attempt_ext_key' => 'http://lrs.learninglocker.net/define/extensions/moodle_feedback_attempt',
            'attempt_name' => $opts['attempt']->name,
            'attempt_score_raw' => $feedback->score->raw,
            'attempt_score_min' => $feedback->score->min,
            'attempt_score_max' => $feedback->score->max,
            'attempt_score_scaled' => $feedback->score->scaled,
            'attempt_success' => null,
            'attempt_completed' => true,
            'attempt_duration' => null,
            'time' => date('c', $opts['attempt']->timemodified)
        ])];
    }

    /**
     * Converts a outputs feedback question and result data in a more manageable format
     * @param [Array => Mixed] $opts
     * @return [PHPObj => Mixed]
     */
    public function parse_feedback($opts) {
        $parsedquestions = [];
        $scoremax = 0;
        $scoreraw = 0;

        foreach ($opts['questions'] as $item => $question) {
            // Find the response to the current question.
            $currentresponse = null;
            foreach ($opts['attempt']->responses as $responseid => $response) {
                if (!empty($response->item) && $response->item == $item) {
                    $currentresponse = $response;
                }
            }

            if (is_null($currentresponse)) {
                // Perhaps a label or the learner did not answer this question - don't add to the array.
                break;
            }

            // Parse the current question.
            $parsedquestion = (object)[
                'question' => $question,
                'options' => $this->parse_question_presentation($question->presentation, $question->typ),
                'score' => (object) [
                    'max' => 0,
                    'raw' => 0
                ],
                'response' => null
            ];

            $parsedquestion->response = $currentresponse->id;

            // Add scores and response.
            foreach ($parsedquestion->options as $optionindex => $option) {
                if (isset($option->value) && $option->value > $parsedquestion->score->max) {
                    $parsedquestion->score->max = $option->value;
                }

                // Find the option the learner selected.
                if ($optionindex == $currentresponse->id) {
                    if (isset($option->value)) {
                        $parsedquestion->score->raw = $option->value;
                    }
                }
            }

            $scoremax += $parsedquestion->score->max;
            $scoreraw += $parsedquestion->score->raw;

            if ($parsedquestion->score->max == 0) {
                $parsedquestion->score->max = null;
                $parsedquestion->score->raw = null;
            } else {
                $parsedquestion->score->min = 0;
                $parsedquestion->score->scaled = $parsedquestion->score->raw / $parsedquestion->score->max;
            }

            array_push(
                $parsedquestions,
                $parsedquestion
            );
        }

        $scoremin = null;
        $scorescaled = null;
        if ($scoremax == 0) {
            $scoremax = null;
            $scoreraw = null;
        } else {
            $scorescaled = $scoreraw / $scoremax;
            $scoremin = 0;
        }

        return (object)[
            'questions' => $parsedquestions,
            'score' => (object) [
                'max' => $scoremax,
                'raw' => $scoreraw,
                'min' => $scoremin,
                'scaled' => $scorescaled
            ]
        ];
    }

    /**
     * Converts a feedback item "presentation" string into an array
     * @param [String => Mixed] $presentation
     * @param [String => Mixed] $type
     * @return [Array => Mixed]
     */
    protected function parse_question_presentation($presentation, $type) {

        // Text areas don't have options or scores.
        if ($type == 'textarea') {
            return [];
        }

        // Strip out the junk.
        $presentation = str_replace('r>>>>>', '', $presentation);
        $presentation = trim(preg_replace('/\s+/', ' ', $presentation));
        $presentation = strip_tags($presentation);

        $options = explode('|', $presentation);
        $return = [(object)[
            'description' => 'Not selected'
        ]];

        foreach ($options as $index => $option) {
            switch ($type) {
                case 'multichoice':
                    array_push($return, (object)[
                        'description' => $option
                    ]);
                    break;
                case 'multichoicerated':
                    $optionarr = explode('#### ', $option);
                    array_push($return, (object)[
                        'description' => $optionarr[1],
                        'value' => $optionarr[0]
                    ]);
                    break;
                default:
                    // Unsupported type.
                    return [];
                    break;
            }
        }

        return $return;
    }

}
