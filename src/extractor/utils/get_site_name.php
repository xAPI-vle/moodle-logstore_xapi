<?php

namespace extractor\utils;

function get_site_name($site) {
    return $site->fullname ?: 'A Moodle site';
}
