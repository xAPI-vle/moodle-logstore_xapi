<?php namespace logstore_emitter\moodle;
use \stdClass as php_obj;

class repository extends php_obj {
    protected $store;
    protected $cfg;

    /**
     * Constructs a new repository.
     * @param $store
     * @param php_obj $cfg
     */
    public function __construct($store, php_obj $cfg) {
        $this->store = $store;
        $this->cfg = $cfg;
    }

    /**
     * Reads an object from the store with the given id.
     * @param string $id
     * @param string $type
     * @return php_obj
     */
    public function read_object($id, $type) {
        $model = $this->store->get_record($type, ['id' => $id]);
        return $model;
    }

    /**
     * Reads an object from the store with the given id.
     * @param string $id
     * @param string $type
     * @return php_obj
     */
    public function read_module($id, $type) {
        $model = $this->read_object($id, $type);
        $module = $this->store->get_record('modules', ['name' => $type]);
        $course_module = $this->store->get_record('course_modules', [
            'instance' => $id,
            'module' => $module->id,
            'course' => $model->course
        ]);
        $model->url = $this->cfg->wwwroot . '/mod/'.$type.'/view.php?id=' . $course_module->id;
        return $model;
    }

    /**
     * Reads a quiz attempt from the store with the given id.
     * @param string $id
     * @return php_obj
     */
    public function read_attempt($id) {
        $model = $this->read_object($id, 'quiz_attempts');
        $model->url = $this->cfg->wwwroot . '/mod/quiz/attempt.php?attempt='.$id;
        $model->name = 'Attempt '.$id;
        return $model;
    }

    /**
     * Reads a course from the store with the given id.
     * @param string $id
     * @return php_obj
     */
    public function read_course($id) {
        $model = $this->read_object($id, 'course');
        $model->url = $this->cfg->wwwroot.($id > 0 ? '/course.php?id=' . $id : '');
        return $model;
    }

    /**
     * Reads a user from the store with the given id.
     * @param string $id
     * @return php_obj
     */
    public function read_user($id) {
        $model = $this->read_object($id, 'user');
        $model->url = $this->cfg->wwwroot;
        return $model;
    }
}
