<?php
class CustomObject {

    public function __construct($names = [])
    {
        foreach ($names as $name) {
            $name = trim($name);
            $this->$name = null;
        }
    }

    public function set($name, $value = null)
    {
        $this->$name = $value;
    }

    public function get($name, $returnValue = null)
    {
        if ($this->exist($name) && $returnValue && !$this->$name) {
            return $returnValue;
        }
        return $this->exist($name) ? $this->$name : $returnValue;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function exist($name): bool
    {
        return property_exists($this, $name);
    }

    public function delete($name)
    {
        unset($this->$name);
    }

    public function setObjectFromArray($array) {
        foreach ($array as $name => $value) {
            $this->set($name, $value);
        }
    }
}
