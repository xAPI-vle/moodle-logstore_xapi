<?php namespace MXTranslator\Events;

class FacetofaceAttend extends FacetofaceEnrol {

    protected $sessionDuration ;
    protected $statuscodes;
    protected $partialAttendanceDurationCredit;

    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override FacetofaceEnrol
     */
    public function read(array $opts) {
        $this->statuscodes = (object)[
            'attended' => 100,
            'partial' => 90
        ];
        $this->partialAttendanceDurationCredit = 0.5;

        $this->sessionDuration = 0;
        foreach ($opts['session']->dates as $index => $date) {
            $this->sessionDuration -= $date->timestart;
            $this->sessionDuration += $date->timefinish;
        }

        $translatorevents = [];
        foreach ($opts['signups'] as $signupIndex => $signup) {
            $signupEvent = $this->getSignupEvent($signup, $opts);
            if (!is_null($signupEvent)) {
                $translatorevent = array_merge(parent::read($opts)[0], $signupEvent);
                array_push($translatorevents,$translatorevent);
            }
        }
        return $translatorevents;
    }

    /**
     * Create an event for a signup or null if no event is required.
     * @param [String => Mixed] $signup
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     */
    private function getSignupEvent($signup, $opts) {

        $currentStatus = null;
        $previousAttendance = false;
        $previousPartialAttendance = false;
        foreach ($signup->statuses as $status) {
            if ($status->timecreated == $opts['event']['timecreated']) {
                $currentStatus = $status;
            } else if ($status->timecreated < $opts['event']['timecreated'] 
                && $status->statuscode == $this->statuscodes->partial) {
                $previousPartialAttendance = true;
            } else if ($status->timecreated < $opts['event']['timecreated'] 
                && $status->statuscode == $this->statuscodes->attended) {
                $previousAttendance = true;
            }
        }

        if (is_null($currentStatus)){
            // There is no status with a timestamp matching the event.
            return null;
        }

        $duration = null;
        $completion = null;
        if ($currentStatus->statuscode == $this->statuscodes->attended){
            if ($previousAttendance == true){
                // Attendance has already been recorded for this user and session.'
                return null;
            }
            $duration = $this->sessionDuration;
            $completion = true;
        } else if ($currentStatus->statuscode == $this->statuscodes->partial){
            if ($previousPartialAttendance == true){
                // Partial attendance has already been recorded for this user and session.
                return null;
            }
            $duration = $this->sessionDuration * $this->partialAttendanceDurationCredit;
            $completion = false;
        } else {
            // This user did not attend this session.
            return null;
        }

        return [
            'recipe' => 'training_session_attend',
            'attendee_id' => $signup->attendee->id,
            'attendee_url' => $signup->attendee->url,
            'attendee_name' => $signup->attendee->fullname,
            'attempt_duration' => "PT".(string) $duration."S",
            'attempt_completion' => $completion
        ];
    }
}