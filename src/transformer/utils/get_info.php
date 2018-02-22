<?php

namespace transformer\utils;

function get_info(array $config) {
    return [
        $config['source_url'] => $config['source_version'],
        $config['plugin_url'] => $config['plugin_version'],
    ];
}
