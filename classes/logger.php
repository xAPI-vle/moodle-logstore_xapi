<?php namespace logstore_emitter;

class logger {
    public static function log($message) {
        return static::write($message, 'log');
    }
    public static function error($message) {
        return static::write($message, 'error');
    }
    private static function write($message, $type) {
        $message = is_string($message) ? $message : json_encode($message, JSON_PRETTY_PRINT);
        $message = '['.(date('c')).'] '.$message.PHP_EOL;
        $dir = __DIR__.'/logs';
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        file_put_contents($dir.'/'.$type.'.txt', $message, FILE_APPEND);
    }
}