<?php

namespace transformer;

require_once(__DIR__ . '/../../vendor/autoload.php');

$statement = events\course_viewed([]);
$encoded_statement = json_encode($statement, JSON_PRETTY_PRINT);
echo($encoded_statement);
