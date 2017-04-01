<?php

namespace Leon\Form\Element;

use Leon\Form\Element\Element;
use Leon\Form\Choice\ValueLabel;
use Leon\Form\Validator;
use Leon\Utility\Utility;

/**
 * Select
 *
 * @author Nick Wakeman <nick@thehiredgun.tech>
 * @since  1.0.0 (2017-03-03)
 */
class Select extends Element
{
    protected $type = 'select';
    protected $template = '@Leon/form/element/select.html.twig';
    protected $name;
    protected $label;
    protected $showLabel = true;
    protected $isRequired = true;
    protected $options = [];
    protected $default;

    public function __construct(string $name, array $options)
    {
        $this->name = $name;
        $this->label = Utility::getLabelFromName($name);
        foreach ($options as $option) {
            $this->options[] = new ValueLabel($option);
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

    public function getOptions()
    {
        return $this->options;
    }

    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    public function getDefault()
    {
        return $this->default;
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
        $rawValue = $validator->getSource($this->name);
        $match = false;
        foreach ($this->options as $option) {
            if ((string) $option->getValue() === (string) $rawValue) {
                $validator->setData($this->name, $option->getValue());
                $match = true;
            }
        }
        if ($this->isRequired && !$match) {
            $validator->setError($this->name, "You must select a choice for $this->label");
        }
    }
}
