<?php

namespace Leon\Form\Choice;

use Exception;

/**
 * ValueLabel
 *
 * @author Nick Wakeman <nick@thehiredgun.tech>
 * @since  1.0.0 (2017-03-03)
 */
class ValueLabel
{
    protected $value;
    protected $label;

    public function __construct($array)
    {
        if (!isset($array['value']) || !isset($array['label'])) {
            Throw new Exception("ValueLabel choices, options, and suggestions must be an array with keys 'value' and 'label'");
        } else {
            $this->value = $array['value'];
            $this->label = $array['label'];
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getLabel()
    {
        return $this->label;
    }
}
