<?php namespace LogExpander\Events;

class DiscussionEvent extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        $discussion = $this->repo->readDiscussion($opts['objectid']);
        return array_merge(parent::read($opts), [
            'discussion' => $discussion,
            'module' => $this->repo->readModule($discussion->forum, 'forum'),
        ]);
    }
}