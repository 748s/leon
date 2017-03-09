<?php

namespace Leon\Form\Element;

class Text extends StringElement
{
    protected $type = 'text';
    protected $template = '@Leon/form/element/text.html.twig';
    protected $maxLength = 255;
}
