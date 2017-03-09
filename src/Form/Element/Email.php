<?php

namespace Leon\Form\Element;

use Leon\Form\Validator;

class Email extends StringElement
{
    protected $type = 'email';
    protected $template = '@Leon/form/element/email.html.twig';
    protected $maxLength = 254;

    public function validate(Validator $validator)
    {
        $emailAddress = trim($validator->getSource($this->name));
        if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            $error = "$this->label needs to be a valid email address";
            $validator->setError($this->name, $error);
        } else {
            $validator->setData($this->name, $emailAddress);
        }
    }
}
