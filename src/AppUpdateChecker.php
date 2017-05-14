<?php
namespace Jaculus;

class AppUpdateChecker {
    private $files;
    private $timestamp_store;
    private $created_timestamp_file;

    public function __construct($timestamp_store, array $files) {
        $this->files = $files;
        $this->timestamp_store = $timestamp_store;

        if(!file_exists($timestamp_store)) {
            $this->updated_timestamps();
            $this->created_timestamp_file = true;
        } else
            $this->created_timestamp_file = false;
    }

    public function hasChanged() {
        if($this->created_timestamp_file) {
            $this->created_timestamp_file = false;
            return true;
        }

        $timestamps_stored = require $this->timestamp_store;
        $new_timestamps = $this->updated_timestamps();

        foreach($new_timestamps as $file => $timestamp) {
            if(isset($timestamps_stored[$file]) && $timestamp > $timestamps_stored[$file])
                return true;
        }

        return false;
    }

    private function updated_timestamps() {
        $result = [];
        foreach($this->files as $file)
            $result[$file] = filemtime($file);
        file_put_contents($this->timestamp_store, '<?php return ' . var_export($result, true) . ';');
        return $result;
    }
}