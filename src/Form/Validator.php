<?php

namespace Leon\Form;

class Validator
{
    protected $data = [];
    protected $errors = [];
    protected $source = [];

    public function __construct($source = [])
    {
        $this->source = ($source) ? $source : $_POST;
    }

    public function getSource($index = null)
    {
        if ($index) {
            return (isset($this->source[$index])) ? $this->source[$index] : null;
        } else {
            return $this->source;
        }
    }

    public function setData($index, $value)
    {
        $this->data[$index] = $value;
    }

    public function getData($index = null)
    {
        if ($index) {
            return (isset($this->data[$index])) ? $this->data[$index] : null;
        } else {
            return $this->data;
        }
    }

    public function setError($index, $error)
    {
        $this->errors[$index] = $error;
    }

    public function getErrors($index = null)
    {
        if ($index) {
            return (isset($this->errors[$index])) ? $this->errors[$index] : null;
        } else {
            return $this->errors;
        }
    }
}
