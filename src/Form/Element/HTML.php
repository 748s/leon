<?php

namespace Leon\Form\Element;

use Leon\Form\Element\Element;

class HTML extends Element
{
    protected $type = 'html';
    protected $template = '@Leon/form/element/html.html.twig';
    protected $html;

    public function __construct($html)
    {
        $this->html = $html;
    }

    public function getHtml()
    {
        return $this->html;
    }
}
