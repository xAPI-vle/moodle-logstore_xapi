<?php
/**
 * Created by PhpStorm.
 * User: lkirkland
 * Date: 7/9/2018
 * Time: 11:00
 */

namespace src\transformer\utils;


function get_attempt_responses(array $config, $question_attempt, $lang) {
    $repo = $config['repo'];
    $retval = array();

    $question = $repo->read_records('question', ['id' => $question_attempt->questionid]);


    $answers = $repo->read_records('question_answers', ['question' => $question_attempt->questionid]);
    $retval['choices'] = array();
    foreach ($answers as $answer) {
        $retval['choices'][] = [
            'description' => [
                $lang => $answer->answer,
            ],
            "id" => "moodle_quiz_question_answer_" . $answer->id,
        ];
    };
    $retval['correctResponsesPattern'] = [$question_attempt->rightanswer];
    $retval['description'] = [$lang => $question->questionsummary];
    $retval['interactionType'] = $question->qtype;
    return $retval;
}