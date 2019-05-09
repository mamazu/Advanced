<?php
/**
 * Advanced microFramework
 * -
 * @copyright Copyright (c) 2019 Advanced microFramework
 * @author    Advanced microFramework Team (Denzel Code, Soul)
 */

namespace advanced\data;

use advanced\utils\File;

/**
* Config class
*/
class Config {

    private $data = [];

    private $file = null;

    private static $instance;

    public function __construct(string $file = null, bool $defaults = null) {
        // Instance
        self::$instance = $this;

        $this->data = [];

        $this->file = $file;

        if ($file) $this->getJSON($file);
    }

    public function getInstance() : Config {
        return self::$instance;
    }

    public function set(string $name, $value) {
        $this->data[$name] = $value;
    }

    public function save() {
        File::write($this->file . '.json', json_encode($this->data, JSON_PRETTY_PRINT));
    }

    public function get(string $name, $default = null) {
        if (!is_array($this->data)) $this->data = [];

        if (array_key_exists($name, $this->data)) return $this->data[$name];

        if (strpos($name, '.') === false) return $default;

        $values = $this->data;

        foreach (explode('.', $name) as $segment) {
            if (!is_array($values) || !array_key_exists($segment, $values)) return $default;

            $values = $values[$segment];
        }

        return $values;
    }

    private function getJSON() : void {
        $file = $this->file . '.json';

        File::check($file, '{}');

        // Start
        ob_start();
        // Include
        include($file);
        // Content
        $data = ob_get_contents();
        // Clean
        ob_end_clean();

        $this->data = json_decode($data, true);
    }

    public function delete(string $name) {
        unset($this->data[$name]);
    }
}
