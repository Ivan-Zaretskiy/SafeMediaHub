<?php

class CustomObject {

    private stdClass $data;

    public function __construct($names = []) {
        $this->data = new stdClass();
        foreach ($names as $name) {
            $name = trim($name);
            $this->data->$name = null;
        }
    }

    public function set($name, $value = null) {
        $this->data->$name = $value;
    }

    public function get($name, $returnValue = null) {
        if ($this->exist($name) && $returnValue && !$this->data->$name) {
            return $returnValue;
        }
        return $this->exist($name) ? $this->data->$name : $returnValue;
    }

    public function __get($name) {
        return $this->get($name);
    }

    public function exist($name): bool {
        return property_exists($this->data, $name);
    }

    public function delete($name) {
        unset($this->data->$name);
    }

    public function setObjectFromArray($array) {
        foreach ($array as $name => $value) {
            $this->set($name, $value);
        }
    }
}
