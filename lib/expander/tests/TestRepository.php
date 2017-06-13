<?php namespace LogExpander\Tests;
use \LogExpander\Repository as MoodleRepository;
use \stdClass as PhpObj;

class TestRepository extends MoodleRepository {

    protected $fakeMoodleDatabase;

    function __construct($store, PhpObj $cfg) {
        parent::__construct($store, $cfg);
        $file = file_get_contents(__DIR__ ."/fakeDB.json");
        $this->fakeMoodleDatabase = json_decode($file, true);
   }

    /**
     * Reads an object from the store with the given id.
     * @param string $type
     * @param [string => mixed] $query
     * @return php_obj
     * @override MoodleRepository
     */
    protected function readStoreRecord($type, array $query, $index = 0) {
        $records = $this->readStoreRecords($type, $query);
        if (is_array($records)) {
            return reset($records);
        }
        return $records;
    }

    /**
     * Reads an array of objects from the store with the given type and query.
     * @param String $type
     * @param [String => Mixed] $query
     * @return PhpArr
     * @override MoodleRepository
     */
    protected function readStoreRecords($type, array $query) {
        $records = $this->fakeMoodleDatabase[$type];
        $matchingRecords = [];

        foreach ($records as $record) {
            foreach ($query as $key => $value) {
                if ($record[$key] === $value) {
                    $record['type'] = 'object'; // Required for assertRecord in EventTest.php to pass, but what's the purpose of including and testing this? 
                    $matchingRecords[$record['id']] = (object) $record;
                }
            }
        }

        // If no matching records found, try to create some!
        if (count($matchingRecords) == 0) {
            foreach ($records as $record) {
                $record['type'] = 'object'; 
                $id = $record['id'];
                foreach ($query as $key => $value) {
                    $record[$key] = $value;
                }
                $matchingRecords[$id] = (object) $record;
            }
        }

        // Always return at least 2 records.
        if (count($matchingRecords) == 1) {
            $newRecord = clone(reset($matchingRecords));
            $newId = strval(intval($newRecord->id) + 1);
            $newRecord->id = $newId;
            $matchingRecords[$newId] = $newRecord;
        }

        return $matchingRecords;
    }

    protected function fullname($user) {
        return "test_fullname";
    }

    /**
     * Reads an object from the store with the given id.
     * @param string $id
     * @param string $type
     * @return php_obj
     */
    public function readObject($id, $type) {
        $model = $this->readStoreRecord($type, ['id' => $id]);
        $model->id = $id;
        return $model;
    }
}
