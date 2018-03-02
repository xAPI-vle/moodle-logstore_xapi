<?php

namespace transformer\utils;

function get_scorm_scoes(array $config, $scoid){
    $model = $config['repo']->read_object($scoid, 'scorm_scoes');
    return $model;
}