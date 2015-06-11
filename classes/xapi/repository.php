<?php namespace logstore_emitter\xapi;
use \TinCan\Statement as tincan_statement;
use \TinCan\RemoteLRS as tincan_remote_lrs;
use \stdClass as php_obj;

class repository extends php_obj {
    /**
     * Constructs a new repository.
     * @param tincan_remote_lrs $store
     */
    public function __construct(tincan_remote_lrs $store) {
        $this->store = $store;
    }

    /**
     * Creates an event in the store.
     * @param [string => mixed] $event
     * @return [string => mixed]
     */
    public function create_event(array $event) {
        $response = $this->store->saveStatement(new tincan_statement($event));
        if ($response->success === false) {
            \logstore_emitter\logger::log('tincan_remote_lrs error.');
            \logstore_emitter\logger::error([
                'response' => isset($response->content) ? $response->content : $response,
            ]);
            
        } else {
            \logstore_emitter\logger::log('Statement sent.');
        }
        return $event;
    }
}
