<?php

namespace Leon\Form\Element;

use Leon\Form\Element\Element;
use Leon\Utility\Utility;

class File extends Element
{
    protected $type = 'file';
    protected $template = '@Leon/form/element/file.html.twig';
    protected $multipartFormData = true;
    protected $name;
    protected $label;
    protected $showLabel = true;

    public function __construct($name)
    {
        $this->name = $name;
        $this->label = Utility::getLabelFromName($name);
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

    public function getMultipartFormData()
    {
        return $this->multipartFormData;
    }
}
