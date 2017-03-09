<?php

namespace Leon\Form\Element;

use Leon\Form\Element\Element;
use Leon\Form\Validator;

/**
 * Hidden
 *
 * @author Nick Wakeman <nick@thehiredgun.tech>
 * @since  1.0.0 (2017-03-03)
 */
class Hidden extends Element
{
    protected $type = 'hidden';
    protected $template = '@Leon/form/element/hidden.html.twig';
    protected $value;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function validate(Validator $validator)
    {
        $validator->setData($this->name, $this->value);
    }
}
