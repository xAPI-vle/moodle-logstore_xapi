<?php namespace Tests\Moodle;
use \logstore_emitter\moodle\repository as moodle_repository;

class TestRepository extends moodle_repository {
    /**
     * Reads an object from the store with the given id.
     * @param string $type
     * @param [string => mixed] $query
     * @return php_obj
     * @override moodle_repository
     */
    protected function read_store($type, array $query) {
        return (object) [
            'id' => 1,
            'username' => 'test_username',
            'lang' => 'test_lang',
            'fullname' => 'test_fullname',
            'summary' => 'test_summary',
            'name' => 'test_name',
            'intro' => 'test_intro',
            'timestart' => 1433946701,
            'timefinished' => 1433946702,
            'course' => 1,
            'sumgrades' => 1,
            'grade' => 2,
            'quiz' => 1,
            'assignment' => 1,
        ];
    }

    /**
     * Reads an object from the store with the given id.
     * @param string $id
     * @param string $type
     * @return php_obj
     */
    public function read_object($id, $type) {
        $model = $this->read_store($type, ['id' => $id]);
        $model->id = $id;
        return $model;
    }
}
