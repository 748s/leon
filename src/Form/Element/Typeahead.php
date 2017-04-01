<?php

namespace Leon\Form\Element;

use Leon\Form\Choice\ValueLabel;
use Leon\Form\Element\Element;
use Leon\Form\Validator;
use Leon\Utility\Utility;

class Typeahead extends Element
{
    protected $type = 'typeahead';
    protected $template = '@Leon/form/element/typeahead.html.twig';
    protected $name;
    protected $label;
    protected $showLabel = true;
    protected $placeholder = true;
    protected $showPlaceholder = true;
    protected $isRequired = true;
    protected $suggestions = [];

    public function __construct($name, array $suggestions)
    {
        $this->name = $name;
        $this->label = Utility::getLabelFromName($name);
        $this->placeholder = $this->label;
        foreach ($suggestions as $suggestion) {
            $this->suggestions[] = new ValueLabel($suggestion);
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

    public function getSuggestions()
    {
        return $this->suggestions;
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

    public function validate(Validator $validator) {
        $source = $validator->getSource($this->getName());
        $value = null;
        foreach ($this->suggestions as $suggestion) {
            if ($source === $suggestion->getValue()) {
                $value = $suggestion->getValue();
                $validator->setData($this->name, $value);
            }
        }
        if ($this->isRequired && is_null($value)) {
            $validator->setError($this->name, "$this->name is a required field");
        }
    }
}
