<?php
/**
 * Created by PhpStorm.
 * User: lkirkland
 * Date: 7/9/2018
 * Time: 11:00
 */

namespace src\transformer\utils;


function get_attempt_responses(array $config, $attempt, $grade_item) {
    $repo = $config['repo'];
    $retval = array();
    $questions = $repo->read_records('question_attempts', ['questionusageid' => $attempt->id]);
    foreach ($questions as $question) {
        $result = $repo->read_records('question_attempt_steps', ['question' => $question->id]);
        $answer_result = 'gaveup';
        // Since the sequence numbers are always 0-3 the last element should be either 2 or 3 depending if they completed
        // the question or gave up.
        $result_last = array_pop($result);
        if ($result_last->sequencenumber != $answer_result) {
            $answer_result = $result_last->state;
        }
        $answers = get_answers($config, $question);
        $retval["question_$question->id"] = array(
            "question" => $question->questionsummary,
            "correct_response" => $question->rightanswer,
            "user_response" => $question->responsesummary,
            "potential_answers" => $answers,
            "question_result" => $answer_result);
    }
    return $retval;
}

function get_answers(array $config, $question) {
    $answers = array();
    $repo = $config['repo'];
    $answer_records = $repo->read_records('question_answers', ['question' => $question->id]);
    foreach ($answer_records as $answer) {
        $answers[] = $answer->answer;
    }
    return $answers;
}