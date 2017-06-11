<?php namespace XREmitter\Events;

class ScormEvent extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        return array_merge(parent::read($opts), [
            'object' => $this->readModule($opts),
            'context' => [
                'contextActivities' => [
                    'grouping' => [
                        $this->readCourse($opts),
                        $this->readScormScoes($opts),
                    ],
                ],
            ],
        ]);
    }

    protected function readScormVerb($opts) {
        $scormStatus = $opts['scorm_status'];
        $verbBaseUrl = 'http://adlnet.gov/expapi/verbs/';
        $verb = array();

        switch ($scormStatus) {
            case 'failed':
                $verbUrl = $verbBaseUrl . $scormStatus;
                $verb = $scormStatus;
                break;
            case 'passed':
                $verbUrl = $verbBaseUrl . $scormStatus;
                $verb = $scormStatus;
                break;
            default:
                $verbUrl = $verbBaseUrl . 'completed';
                $verb = 'completed';
        }

        static::$verb_display = ['en' => $verb];

        $lang = [
            'id' => $verbUrl,
            'display' => $this->readVerbDisplay($opts),
        ];

        return $lang;
    }

    protected function readScormScoes($opts) {
        return [
            'id' => $opts['module_url'],
            'definition' => [
                'type' => $opts['scorm_scoes_type'],
                'name' => [
                    $opts['context_lang'] => $opts['scorm_scoes_name'],
                ],
                'description' => [
                    $opts['context_lang'] => $opts['scorm_scoes_description'],
                ],
            ],
        ];
    }
}

