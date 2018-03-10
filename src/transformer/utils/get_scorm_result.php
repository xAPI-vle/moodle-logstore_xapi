<?php

namespace transformer\utils;

function get_scorm_result($scormscoestrack, $cmiunserialized){

    $scoremax = $scormscoestrack['scoremax'];
    $scoreraw = $cmiunserialized['cmivalue'];
    $scoremin = $scormscoestrack['scoremin'];
    $scorescaled = null;

    $scorescaled = $scoreraw >= 0 ? ($scoreraw / $scoremax) : ($scoreraw / $scoremin);

    return [
        'score' => [
            'raw' => $scoreraw,
            'min' => $scoremin,
            'max' => $scoremax,
            'scaled' => $scorescaled
        ],
    ];
}