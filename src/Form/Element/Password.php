<?php

namespace Leon\Form\Element;

use Leon\Form\Validator;

class Password extends StringElement
{
    protected $type = 'password';
    protected $template = '@Leon/form/element/password.html.twig';
    protected $maxLength = 255;

    public function validate(Validator $validator)
    {
        $error = null;
        $string = trim($validator->getSource($this->name));
        $validator->setData($this->name, $string);
        if ($this->minLength) {
            if (mb_strlen($string) < $this->minLength) {
                if (1 === $this->minLength) {
                    $error = "$this->label is required";
                } else {
                    $error = "$this->label must be at least $this->minLength characters long";
                }
            }
        }
        if (mb_strlen($string) > $this->maxLength) {
            $error = "$this->label cannot be more than $this->maxLength characters long";
        }
        if ($error) {
            $validator->setError($this->name, $error);
        }
    }
}
