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

namespace MXTranslator\Events;

defined('MOODLE_INTERNAL') || die();

class FacetofaceAttend extends FacetofaceEnrol {

    protected $sessionduration;
    protected $statuscodes;
    protected $partialattendancedurationcredit;

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
        foreach ($opts['signups'] as $signupindex => $signup) {
            $signupevent = $this->get_signup_event($signup, $opts);
            if (!is_null($signupevent)) {
                $translatorevent = array_merge(parent::read($opts)[0], $signupevent);
                array_push($translatorevents, $translatorevent);
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
    private function get_signup_event($signup, $opts) {
        $currentstatus = null;
        $previousattendance = false;
        $previouspartialattendance = false;
        foreach ($signup->statuses as $status) {
            if ($status->timecreated == $opts['event']['timecreated']) {
                $currentstatus = $status;
            } else if ($status->timecreated < $opts['event']['timecreated']
                && $status->statuscode == $this->statuscodes->partial) {
                $previouspartialattendance = true;
            } else if ($status->timecreated < $opts['event']['timecreated']
                && $status->statuscode == $this->statuscodes->attended) {
                $previousattendance = true;
            }
        }

        if (is_null($currentstatus)) {
            // There is no status with a timestamp matching the event.
            return null;
        }

        $duration = null;
        $completion = null;
        if ($currentstatus->statuscode == $this->statuscodes->attended) {
            if ($previousattendance == true) {
                // Attendance has already been recorded for this user and session.'
                return null;
            }
            $duration = $this->sessionDuration;
            $completion = true;
        } else if ($currentstatus->statuscode == $this->statuscodes->partial) {
            if ($previouspartialattendance == true) {
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
