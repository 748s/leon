<?php

namespace Leon\Form\Element;

use Exception;
use Leon\Form\Element\Element;
use Leon\Form\Choice\ValueLabel;
use Leon\Form\Validator;
use Leon\Utility\Utility;

/**
 * CheckboxArrayGroup
 *
 * @author Nick Wakeman <nick@thehiredgun.tech>
 * @since  1.0.0 (2017-03-03)
 */
class CheckboxArrayGroup extends Element
{
    protected $type = 'checkboxArrayGroup';
    protected $template = '@Leon/form/element/checkboxArrayGroup.html.twig';
    protected $name;
    protected $label;
    protected $showLabel = true;
    protected $numRequired = 1;
    protected $checkboxes = [];

    public function __construct(string $name, array $checkboxes, string $label = '')
    {
        $this->name = $name;
        $this->label = $label ? $label : Utility::getLabelFromName($name);
        $this->setCheckboxes($checkboxes);
    }

    public function getName()
    {
        return $this->name;
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

    public function setNumRequired(int $numRequired)
    {
        $this->numRequired = $numRequired;

        return $this;
    }

    public function getNumRequired()
    {
        return $this->numRequired;
    }

    public function setCheckboxes(array $checkboxes)
    {
        foreach ($checkboxes as $checkbox) {
            $this->checkboxes[] = new ValueLabel($checkbox);
        }
    }

    public function getCheckboxes()
    {
        return $this->checkboxes;
    }

    public function validate(Validator $validator)
    {
        $rawValues = $validator->getSource($this->name);
        if (!is_array($rawValues)) {
            $rawValues = [];
        }
        $selectedValues = [];
        $numSelected = 0;
        foreach ($this->checkboxes as $checkbox) {
            if (in_array($checkbox->getValue(), $rawValues)) {
                $selectedValues[] = $checkbox->getValue();
                $numSelected++;
            }
        }
        $validator->setData($this->name, $selectedValues);
        if ($numSelected < $this->numRequired) {
            $choices = (1 == $this->numRequired) ? 'choice' : 'choices';
            $validator->setError($this->name, "You must select at least $this->numRequired $choices for $this->label");
        }
    }
}
