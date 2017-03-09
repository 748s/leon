<?php

namespace Leon\Form\Element;

use Exception;
use Leon\Form\Element\Element;
use Leon\Form\Validator;
use Leon\Utility\Utility;

/**
 * Text
 *
 * @author Nick Wakeman <nick@thehiredgun.tech
 * @since  1.0.0 (2017-03-03)
 */
class Date extends Element
{
    protected $type = 'date';
    protected $template = '@Leon/form/element/date.html.twig';
    protected $name;
    protected $label;
    protected $showLabel = true;
    protected $placeholder = 'YYYY-MM-DD';
    protected $showPlaceholder = true;
    protected $maxLength = 10;
    protected $minLength = 10;

    public function __construct($name, $label = '')
    {
        $this->name = $name;
        $this->label = $label ? $label : Utility::getLabelFromName($name);
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

    public function setPlaceholder(string $placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    public function setShowPlaceholder(bool $showPlaceholder)
    {
        $this->showPlaceholder = $showPlaceholder;

        return $this;
    }

    public function getShowPlaceholder()
    {
        return $this->showPlaceholder;
    }

    public function validate(Validator $validator)
    {
        $date = trim($validator->getSource($this->name));
        $dateArray = explode('-', $date);
        if (3 !== count($dateArray)) {
            $validator->setError($this->name, "A valid date is required for $this->label");
        } elseif (!checkdate($dateArray[1], $dateArray[2], $dateArray[0])) {
            $validator->setError($this->name, "A valid date is required for $this->label");
        } else {
            $validator->setData($this->name, $date);
        }
    }
}
