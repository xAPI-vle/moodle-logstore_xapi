<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace src\loader\log;

defined('MOODLE_INTERNAL') || die();

use src\loader\utils as utils;

function load(array $config, array $transformedevents) {
    $statements = array_reduce($transformedevents, function ($result, $transformedevent) {
        $eventstatements = $transformedevent['statements'];
        return array_merge($result, $eventstatements);
    }, []);
    echo(json_encode($statements, JSON_PRETTY_PRINT)."\n");
    return utils\construct_loaded_events($transformedevents, true);
}