<?php namespace logstore_emitter\moodle;
use \stdClass as php_obj;

class object extends php_obj {
    /**
     * Constructs a new object.
     * @param [string => mixed] $event
     * @param string $url
     */
    public function __construct($id, $url) {
        $this->id = $id;
        $this->url = $url;
    }
}
