<?php

namespace transformer\utils;

function get_signup_event($signup, $event, $sessionduration) {
    $currentstatus = null;
    $previousattendance = false;
    $previouspartialattendance = false;
    $partialattendancedurationcredit = 0.5;
    $statuscodepartial = 90;
    $statuscodefull = 100;

    foreach ($signup->statuses as $status) {
        if ($status->timecreated == $event['event']['timecreated']) {
            $currentstatus = $status;
        } else if ($status->timecreated < $event['event']['timecreated']
            && $status->statuscode == $statuscodepartial) {
            $previouspartialattendance = true;
        } else if ($status->timecreated < $event['event']['timecreated']
            && $status->statuscode == $statuscodefull) {
            $previousattendance = true;
        }
    }

    if (is_null($currentstatus)) {
        // There is no status with a timestamp matching the event.
        return null;
    }

    $duration = null;
    $completion = null;
    if ($currentstatus->statuscode == $statuscodefull) {
        if ($previousattendance == true) {
            // Attendance has already been recorded for this user and session.'
            return null;
        }
        $duration = $this->sessionDuration;
        $completion = true;
    } else if ($currentstatus->statuscode == $statuscodepartial) {
        if ($previouspartialattendance == true) {
            // Partial attendance has already been recorded for this user and session.
            return null;
        }
        $duration = $sessionduration * $partialattendancedurationcredit;
        $completion = false;
    } else {
        // This user did not attend this session.
        return null;
    }

    return [
        'attendee_id' => $signup->attendee->id,
        'attempt_duration' => "PT".(string) $duration."S",
        'attempt_completion' => $completion
    ];
}