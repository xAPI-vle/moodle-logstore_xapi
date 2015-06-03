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
 
namespace logstore_xapi;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/config.php');
require_once($CFG->dirroot.'/admin/tool/log/store/xapi/libs/TinCanPHP/autoload.php');

/*
* Make new LRS connection
*
*/
function lrs_connect($endpoint, $username, $pass){
    $lrs = new \TinCan\RemoteLRS(
        $endpoint,
        '1.0.1',
        $username,
        $pass
    );
    return $lrs;
}

/**
* Get record from LRS
*
*/
function get_record(){
    return true;      
}

/*
* Set record in LRS
*
*/
function set_record($activity, $verb_link){
    global $DB;
    $lrs = lrs_connect(
        get_config('logstore_xapi', 'endpoint'),
        get_config('logstore_xapi', 'username'),
        get_config('logstore_xapi', 'password')
    );
    //log the save statement event in Moodle's logstore
    //$activity will be an array filled with the statement
    
    //echo '<pre>', print_r($activity), '</pre>';
    
    $user_info = $DB->get_record('user', array('id'=>$activity['userid']));
    
    $actor = set_xapi_statement_actor(
        $user_info->email, 
        $user_info->username, 
        generate_userprofile_link($user_info->id));
        
    $verb = set_xapi_statement_verb($verb_link, $activity['action']);
    
    //need to get the URL reference here of the activity that initiated the logging event
    $activity = set_xapi_statement_activity($activity);
    
    $statement = new \TinCan\Statement(
        [
            'actor' => $actor,
            'verb'  => $verb,
            'object' => $activity,
            'timestamp' => set_xapi_timestamp(),
        ]
    );
    
    //echo '<pre>', print_r($statement), '</pre>';

    $response = $lrs->saveStatement($statement);
    
    return $response;
}

/*
* Set actor
*
*/
function set_xapi_statement_actor($email, $username, $user_link){
    $actor = new \TinCan\Agent(
        [ 
            'mbox' => 'mailto:' . $email,
            'account' => array(
                'name' => $username,
                'homePage' => $user_link
            )
        ]
    );
    return $actor;
}

/*
* Set verb
*
*/
function set_xapi_statement_verb($verb_link, $verb){
    $verb = new \TinCan\Verb(
        [ 
            'id' => $verb_link,
            'display' => array(
                'en-GB' => $verb,
                'en-US' => $verb,
            ), 
        ]
    );
    return $verb;    
}

/*
* Set object
*
*/
function set_xapi_statement_activity($activity){
    //ID must be a link according to xAPI standards
    //implement try and catch to avoid crash when an ID isn't generated
    $activity_id = generate_activity_id($activity);
    $activity = new \TinCan\Activity(
        [ 
            'id' => $activity_id,
        ]
    );
    return $activity;    
}

/*
 *
 *
 */
 function set_xapi_timestamp(){
    //'2015-02-23T15:20:03.779900+00:00'
    //need to account for timezone
    $timestamp = new \DateTime('NOW');
    return $timestamp->format('c');
 }

/*
 *
 *
 */ 
function generate_userprofile_link($userid) {
    global $CFG;
    $profile_link = $CFG->wwwroot . '/user/profile.php?id=' . $userid;
    return $profile_link;
}

/*
 *
 *
 */
function generate_activity_id($activity) {
    $data = array();
    $data['eventname'] = $activity['eventname'];
    $data['component'] = $activity['component'];
    $data['action'] = $activity['action'];
    $data['target'] = $activity['target'];
    $data['objecttable'] = $activity['objecttable'];
    $data['objectid'] = $activity['objectid'];
    $data['crud'] = $activity['crud'];
    $data['edulevel'] = $activity['edulevel'];
    $data['contextid'] = $activity['contextid'];
    $data['contextlevel'] = $activity['contextlevel'];
    $data['contextinstanceid'] = $activity['contextinstanceid'];
    $data['userid'] = $activity['userid'];
    $data['courseid'] = $activity['courseid'];
    $data['relateduserid'] = $activity['relateduserid'];
    $data['anonymous'] = $activity['anonymous'];
    $data['other'] = $activity['other'];
    $data['timecreated'] = $activity['timecreated'];
    
    $logextra = array();
    $logestra['origin'] = $activity['origin'];
    $logestra['ip'] = $activity['ip'];
    $logestra['realuserid'] = $activity['realuserid'];

    if(isset($activity)){
        $url = \core\event\base::restore($data, $logextra)->get_url();
        $eventurl = $url->get_scheme() . "://" . $url->get_host() . $url->get_path() . '?id=' . $url->get_param('id');
        return $eventurl;
    }
    return false;
}
