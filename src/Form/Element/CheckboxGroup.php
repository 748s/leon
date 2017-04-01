<?php

namespace Leon\Form\Element;

use Exception;
use Leon\Form\Element\Element;
use Leon\Form\Choice\NameLabel;
use Leon\Form\Validator;
use Leon\Utility\Utility;

/**
 * CheckboxGroup
 *
 * @author Nick Wakeman <nick@thehiredgun.tech>
 * @since  1.0.0 (2017-03-03)
 */
class CheckboxGroup extends Element
{
    protected $type = 'checkboxGroup';
    protected $template = '@Leon/form/element/checkboxGroup.html.twig';
    protected $name;
    protected $label;
    protected $showLabel = true;
    protected $numRequired = 1;
    protected $checkboxes = [];

    public function __construct(string $name, array $checkboxes)
    {
        $this->name = $name;
        $this->label = Utility::getLabelFromName($name);
        foreach ($checkboxes as $checkbox) {
            $this->checkboxes[] = new NameLabel($checkbox);
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

    public function getCheckboxes()
    {
        return $this->checkboxes;
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

    public function validate(Validator $validator)
    {
        $numSelected = 0;
        foreach ($this->checkboxes as $checkbox) {
            if ((string) $validator->getSource($checkbox->getName()) === '1') {
                $validator->setData($checkbox->getName(), 1);
                $numSelected++;
            } else {
                $validator->setData($checkbox->getName(), 0);
            }
        }
        if ($numSelected < $this->numRequired) {
            $validator->setError($this->name, "You must select at least $this->numRequired choices for $this->label");
        }
    }
}
