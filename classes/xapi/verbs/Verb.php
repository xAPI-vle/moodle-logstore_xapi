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

class Verb
{
    protected $_id;
    
    protected $_name;
    
    protected $_logstoreEventName = array();
    
    public function __construct() {
        return false;
    }
    
    public function get() {
        $object = new stdClass();
        $object->id = $this->_id;
        $object->name = $this->_name;
        //Definitions found at: https://registry.tincanapi.com/#home/verbs
        $object->logstoreEventName = $this->_logstoreEventName;
        return get_object_vars($object);
    }
        
    public function setEventName($logstoreComponent) {
        $this->_logstoreEventName = $this->generateLogstoreEventName($logstoreComponent);
    }
    
    public function getEventName($logstoreComponent) {
        foreach($this->_logstoreEventName as $allowed_event) {
            if($logstoreComponent == $allowed_event) {
                return $allowed_event;
            }   
        }
        return false;
    }
    
    public function getID(){
        return $this->_id;
    }
}