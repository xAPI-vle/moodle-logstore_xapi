<?php namespace LogExpander\Events;

class ScormSubmitted extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        $cmiUnserialized = unserialize($opts['other']);
        $scoid = $opts['contextinstanceid'];
        $scormid = $opts['objectid'];
        $attempt = $cmiUnserialized['attemptid'];
        $scormScoesTrack = $this->repo->readScormScoesTrack(
            $opts['userid'],
            $scormid,
            $scoid,
            $attempt
        );

        return array_merge(parent::read($opts), [
            'module' => $this->repo->readModule($scormid, 'scorm'),
            'scorm_scoes_track' => $scormScoesTrack,
            'scorm_scoes' => $this->repo->readScormScoes($scoid),
            'cmi_data' => $cmiUnserialized,
        ]);
    }
}
