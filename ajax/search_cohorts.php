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

/**
 * AJAX endpoint: search cohorts by name.
 *
 * @package   logstore_xapi
 * @copyright 2025 David Pesce <david.pesce@exputo.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('AJAX_SCRIPT', true);

require_once(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))) . '/config.php');

require_login();

require_sesskey();

$systemcontext = context_system::instance();

require_capability('moodle/site:config', $systemcontext);

$query = optional_param('query', '', PARAM_TEXT);

if (strlen(trim($query)) < 2) {
    echo json_encode([]);
    die;
}

$like = $DB->sql_like('name', ':query', false, false);
$params = ['query' => '%' . $DB->sql_like_escape($query) . '%'];
$sql = "SELECT id, name FROM {cohort} WHERE visible = 1 AND $like ORDER BY name ASC";
$cohorts = $DB->get_records_sql($sql, $params, 0, 20);

$result = [];
foreach ($cohorts as $cohort) {
    $result[] = ['id' => (int)$cohort->id, 'name' => $cohort->name];
}

echo json_encode($result);
