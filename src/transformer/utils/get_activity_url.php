<?php

namespace src\transformer\utils;

function get_activity_url(array $config, $activityType, $activityId) {
    return $config['app_url'].'/'.$activityType.'/view.php?id='.$activityId;
}
