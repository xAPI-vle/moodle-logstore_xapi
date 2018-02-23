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

class ScormScoreRawSubmitted extends ScormEvent {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override ModuleViewed
     */
    public function read(array $opts) {
        $scoremax = null;
        $scoreraw = null;
        $scoremin = null;
        $scorescaled = null;

        if (isset($opts['scorm_scoes_track']['scoremax'])) {
            $scoremax = $opts['scorm_scoes_track']['scoremax'];
        }
        if (isset($opts['cmi_data']['cmivalue'])) {
            $scoremax = $opts['cmi_data']['cmivalue'];
        }
        if (isset($opts['scorm_scoes_track']['scoremin'])) {
            $scoremin = $opts['scorm_scoes_track']['scoremin'];
        }

        if ($scoremax !=0 && $scoremin !=0) {
            $scorescaled = $scoreraw >= 0 ? ($scoreraw / $scoremax) : ($scoreraw / $scoremin);
        }

        return [array_merge(parent::read($opts)[0], [
            'recipe' => 'scorm_scoreraw_submitted',
            'scorm_score_raw' => $scoreraw,
            'scorm_score_min' => $scoremin,
            'scorm_score_max' => $scoremax,
            'scorm_score_scaled' => $scorescaled,
        ])];
    }
}
