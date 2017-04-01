<?php

namespace Leon\Form\Element;

class Element
{
    protected $type;
    protected $template;

    public function getType()
    {
        return $this->type;
    }

    public function setTemplate(string $template)
    {
        $this->template = $template;

        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }    
}
