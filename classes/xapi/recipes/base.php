<?php namespace logstore_emitter\xapi\recipes;
use \TinCan\Statement as tincan_statement;

class base extends tincan_statement {
    const LOG_EXTENSION = 'http://lrs.learninglocker.net/define/extensions/moodle_logstore_standard_log';

    /**
     * Constructs a new statement for viewed.
     * @param [String => Mixed] $options Options to construct the statement with.
     * @override tincan_statement
     */
    public function __construct(array $options) {
        parent::__construct($options);
    }

    /**
     * Reads the language from the opts.
     * @param [string => mixed] $opts
     * @return string
     */
    protected function read_lang(array $opts) {
        return isset($opts['course']->lang) ? $opts['course']->lang : 'en';
    }

    /**
     * Reads the context entensions from the opts.
     * @param [string => mixed] $opts
     * @return string
     */
    protected function read_context_extensions(array $opts) {
        unset($opts['user']);
        unset($opts['course']);
        unset($opts['object']);
        return $opts;
    }    
}
