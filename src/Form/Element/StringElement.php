<?php

namespace Leon\Form\Element;

use Exception;
use Leon\Form\Validator;
use Leon\Utility\Utility;

abstract class StringElement extends Element
{
    protected $name;
    protected $label;
    protected $showLabel = true;
    protected $placeholder;
    protected $showPlaceholder = true;
    protected $template;
    protected $minLength = 1;
    protected $maxLength;
    protected $stripTags = true;
    protected $errorMessage;

    public function __construct($name, $label = '')
    {
        $this->name = $name;
        if (!$label) {
            $this->label = Utility::getLabelFromName($name);
            $this->placeholder = $this->label;
        } else {
            $this->label = $label;
            $this->placeholder = $label;
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

    public function setShowLabel(bool $showLabel)
    {
        $this->showLabel = $showLabel;

        return $this;
    }

    public function getShowLabel()
    {
        return $this->showLabel;
    }

    public function setPlaceholder($placeholder)
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

    public function setMinLength(int $minLength)
    {
        $this->minLength = $minLength;

        return $this;
    }

    public function getMinLength()
    {
        return $this->minLength;
    }

    public function setMaxLength(int $maxLength)
    {
        $this->maxLength = $maxLength;

        return $this;
    }

    public function getMaxLength()
    {
        return $this->maxLength;
    }

    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function setStripTags($stripTags)
    {
        switch (getType($stripTags)) {
            case 'boolean':
            case 'string':
                $this->stripTags = $stripTags;
            break;
            default:
                Throw new Exception('Invalid argument for setStripTags');
            break;
        }

        return $this;
    }

    public function validate(Validator $validator)
    {
        $error = null;
        $string = trim($validator->getSource($this->name));
        if (true === $this->stripTags) {
            $string = strip_tags($string);
        } elseif (is_string($this->stripTags)) {
            $string = strip_tags($string, $this->stripTags);
        }
        $validator->setData($this->name, $string);
        if ($this->minLength) {
            if (mb_strlen($string) < $this->minLength) {
                if (1 === $this->minLength) {
                    $error = "$this->label is required";
                } else {
                    $error = "$this->label must be at least $this->minLength characters long";
                }
            }
        }
        if (mb_strlen($string) > $this->maxLength) {
            $error = "$this->label cannot be more than $this->maxLength characters long";
        }
        if ($error) {
            $validator->setError($this->name, $error);
        }
    }
}
