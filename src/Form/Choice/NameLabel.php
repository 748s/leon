<?php

namespace Leon\Form\Choice;

use Exception;

/**
 * NameLabel
 *
 * @author Nick Wakeman <nick@thehiredgun.tech>
 * @since  1.0.0 (2017-03-03)
 */
class NameLabel
{
    protected $name;
    protected $label;

    public function __construct($array)
    {
        if (!isset($array['name']) || !isset($array['label'])) {
            Throw new Exception("NameLabel choices must be an array with keys 'name' and 'label'");
        } else {
            $this->name = $array['name'];
            $this->label = $array['label'];
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLabel()
    {
        return $this->label;
    }
}
