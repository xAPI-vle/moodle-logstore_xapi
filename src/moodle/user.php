<?php namespace logstore_emitter\moodle;
use \stdClass as php_obj;

class user extends php_obj {
    /**
     * Constructs a new user.
     * @param [string => mixed] $event
     */
    public function __construct($id, $url) {
        $user = $DB->get_record('user', ['id'=>$id]);
        $this->id = $user->id;
        $this->email = $user->email;
        $this->name = $user->username;
        $this->url = $url;
    }
}
