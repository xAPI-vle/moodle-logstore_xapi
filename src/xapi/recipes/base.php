<?php namespace logstore_emitter\xapi\recipes;
use \TinCan\Statement as tincan_statement;

class base extends tincan_statement {
    /**
     * Constructs a new statement for viewed.
     * @param [String => Mixed] $options Options to construct the statement with.
     * @override tincan_statement
     */
    public function __construct(array $options) {
        parent::construct($options);
    }
}
