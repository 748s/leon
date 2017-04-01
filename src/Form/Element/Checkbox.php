<?php

namespace Leon\Form\Element;

use Exception;
use Leon\Form\Element\Element;
use Leon\Form\Validator;
use Leon\Utility\Utility;

/**
 * Checkbox
 *
 * @author Nick Wakeman <nick@thehiredgun.tech>
 * @since  1.0.0 2017-03-03
 */
class Checkbox extends Element
{
    protected $type = 'checkbox';
    protected $name;
    protected $label;
    protected $showLabel = true;
    protected $template = '@Leon/form/element/checkbox.html.twig';
    protected $isRequired = false;

    public function __construct($name)
    {
        $this->name = $name;
        $this->label = Utility::getLabelFromName($name);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setShowLabel(bool $showLabel)
    {
        $this->showLabel = $showLabel;

        return $this;
    }

    public function getShowLabel()
    {
        return $this->showLabel;
    }

    public function setIsRequired(bool $isRequired)
    {
        $this->isRequired = $isRequired;

        return $this;
    }

    public function getIsRequired()
    {
        return $this->isRequired;
    }

    public function validate(Validator $validator)
    {
        $validator->setData($this->name, ((string) $validator->getSource($this->name) === '1') ? 1 : 0);
        if ($this->isRequired && !$validator->getData($this->name)) {
            $validator->setError($this->name, $this->getErrorMessage("You must check the box for %label%"));
        }
    }
}
