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
    $question_attempts = $repo->read_records('question_attempts', ['questionusageid' => $attempt->id]);
    foreach ($question_attempts as $question_attempt) {
        $result = $repo->read_records('question_attempt_steps', ['questionattemptid' => $question_attempt->id]);
        $answer_result = 'gaveup';
        // Since the sequence numbers are always 0-3 the last element should be either 2 or 3 depending if they completed
        // the question or gave up.
        $result_last = array_pop($result);
        if ($result_last->sequencenumber != $answer_result) {
            $answer_result = $result_last->state;
        }
        $answers = get_answers($config, $question_attempt);
        $retval["question_$question_attempt->questionid"] = array(
            "question" => $question_attempt->questionsummary,
            "correct_response" => $question_attempt->rightanswer,
            "user_response" => $question_attempt->responsesummary,
            "potential_answers" => $answers,
            "question_result" => $answer_result);
    }
    return $retval;
}

function get_answers(array $config, $question_attempt) {
    $answers = array();
    $repo = $config['repo'];
    $answer_records = $repo->read_records('question_answers', ['question' => $question_attempt->questionid]);
    foreach ($answer_records as $answer) {
        $answers[] = $answer->answer;
    }
    return $answers;
}