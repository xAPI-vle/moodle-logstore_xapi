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

namespace LogExpander\Events;

defined('MOODLE_INTERNAL') || die();

class ScormSubmitted extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        $cmiunserialized = unserialize($opts['other']);
        $scoid = $opts['contextinstanceid'];
        $scormid = $opts['objectid'];
        $attempt = $cmiunserialized['attemptid'];
        $scormscoestrack = $this->repo->read_scorm_scoes_track(
            $opts['userid'],
            $scormid,
            $scoid,
            $attempt
        );

        return array_merge(parent::read($opts), [
            'module' => $this->repo->read_module($scormid, 'scorm'),
            'scorm_scoes_track' => $scormscoestrack,
            'scorm_scoes' => $this->repo->read_scorm_scoes($scoid),
            'cmi_data' => $cmiunserialized,
        ]);
    }
}
