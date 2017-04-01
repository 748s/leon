<?php

namespace Leon\Form\Element;

use Exception;
use Leon\Form\Element\Element;
use Leon\Form\Choice\ValueLabel;
use Leon\Form\Validator;
use Leon\Utility\Utility;

/**
 * Radio
 *
 * @author Nick Wakeman <nick@thehiredgun.tech>
 * @since  1.0.0 (2017-03-03)
 */
class Radio extends Element
{
    protected $type = 'radio';
    protected $template = '@Leon/form/element/radio.html.twig';
    protected $name;
    protected $label;
    protected $showLabel = true;
    protected $isRequired = true;
    protected $choices = [];

    public function __construct($name, array $choices)
    {
        $this->name = $name;
        $this->label = Utility::getLabelFromName($name);
        foreach ($choices as $choice) {
            $this->choices[] = new ValueLabel($choice);
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setLabel(string $label)
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

    public function getIsRequired()
    {
        return $this->isRequired;
    }

    public function getChoices()
    {
        return $this->choices;
    }

    public function validate(Validator $validator)
    {
        $value = $validator->getSource($this->name);
        $isMatch = false;
        foreach ($this->choices as $choice) {
            if ((string) $choice->getValue() === (string) $value) {
                $isMatch = true;
            }
        }
        if (!$isMatch) {
            $error = "You must select a valid choice for $this->label";
            $validator->setError($this->name, $error);
        } else {
            $validator->setData($this->name, $value);
        }
    }
}
