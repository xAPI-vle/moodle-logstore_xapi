<?php

namespace transformer\utils;

function get_activity_id(array $config, $type, $id) {
    return $config['wwwroot'] . '/mod/'.$type.'/view.php?id='.$id;
}
