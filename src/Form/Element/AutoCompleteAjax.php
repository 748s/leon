<?php

namespace Leon\Form\Element;

use Leon\Form\Choice\ValueLabel;
use Leon\Form\Element\Element;
use Leon\Form\Validator;
use Leon\Utility\Utility;

class AutoCompleteAjax extends Element
{
    protected $type = 'autoCompleteAjax';
    protected $template = '@Leon/form/element/autoCompleteAjax.html.twig';
    protected $name;
    protected $label;
    protected $showLabel = true;
    protected $placeholder = true;
    protected $showPlaceholder = true;
    protected $URL;
    protected $tableName;
    protected $isRequired = true;

    public function __construct(string $name, string $URL, string $tableName)
    {
        $this->name = $name;
        $this->label = Utility::getLabelFromName($name);
        $this->placeholder = $this->label;
        $this->URL = $URL;
        $this->tableName = $tableName;
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

    public function getURL()
    {
        return $this->URL;
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
        global $db;
        $source = $validator->getSource($this->getName());
        if ($db->getOneById($this->tableName, $source)) {
            $validator->setData($this->name, $source);
        } elseif ($this->isRequired) {
            $validator->setError($this->name, "$this->name is a required field");
        }
    }
}
