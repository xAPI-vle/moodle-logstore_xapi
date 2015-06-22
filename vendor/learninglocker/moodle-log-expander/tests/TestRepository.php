<?php namespace Tests;
use \LogExpander\Repository as MoodleRepository;

class TestRepository extends MoodleRepository {
    /**
     * Reads an object from the store with the given id.
     * @param string $type
     * @param [string => mixed] $query
     * @return php_obj
     * @override MoodleRepository
     */
    protected function readStore($type, array $query) {
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
            'userid' => 1,
        ];
    }

    /**
     * Reads an object from the store with the given id.
     * @param string $id
     * @param string $type
     * @return php_obj
     */
    public function readObject($id, $type) {
        $model = $this->readStore($type, ['id' => $id]);
        $model->id = $id;
        return $model;
    }
}
