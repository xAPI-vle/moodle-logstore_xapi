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
 * Internal library of functions for module xapi
 *
 * All the xapi specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    logstore_api
 * @copyright  2015 Jerrett Fowler <jfowler@charitylearning.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace logstore_xapi\xapi\verbs;

defined('MOODLE_INTERNAL') || die();

class attended extends Verb 
{
    public function __construct() {
        $this->_id = 'http://adlnet.gov/expapi/verbs/attended';
        $this->_name['en-US'] = 'attended';
        $this->_logstoreEventName[''] = '';
    }
}